<?php

namespace DTApi\Http\Controllers;

use App\Http\Requests\StoreJobRequest;
use DTApi\Http\DataTransferObjects\AuthenticatedUserDTO;
use DTApi\Http\DataTransferObjects\BookingDTO;
use DTApi\Http\DataTransferObjects\BookingStoreDTO;
use DTApi\Http\Traits\ResponseTrait;
use DTApi\Models\Distance;
use DTApi\Models\Job;
use DTApi\Service\BookingService;
use DTApi\Service\JobService;
use Exception;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

/**
 * Class BookingController
 * @package DTApi\Http\Controllers
 */
class BookingController extends Controller
{
    use ResponseTrait;

    /**
     * @var BookingService $bookingService
     */
    protected $bookingService;

    /**
     * @var JobService $jobService
     */
    protected $jobService;

    /**
     * @param BookingService $bookingService
     * @param JobService $jobService
     */
    public function __construct(BookingService $bookingService, JobService $jobService)
    {
        $this->bookingService = $bookingService;
        $this->jobService = $jobService;
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        # We should use try...catch blocks to handle the exceptions
        try {
            $bookingDTO = new BookingDTO($request);
            # We shouldn't use property name like this "__authenticatedUser", also we should use auth instead of getting the user like this.
            $authenticatedUserDTO = new AuthenticatedUserDTO($request->__authenticatedUser);

            $data = $this->bookingService->getJobs($bookingDTO, $authenticatedUserDTO);
            return $this->successResponse('Jobs fetched successfully.', $data);
        } catch (Exception $e) {
            # Return the exception error message with error status code in the response
            return $this->exceptionToResponse($e);
        }
    }

    /**
     * @param int $id
     * @return JsonResponse
     */
    public function show(int $id): JsonResponse
    {
        try {
            $job = $this->jobService->getJob($id);

            return $this->successResponse('Job fetched successfully.', $job);
        } catch (Exception $e) {
            return $this->exceptionToResponse($e);
        }
    }

    /**
     * @param StoreJobRequest $request
     * @return JsonResponse
     */
    public function store(StoreJobRequest $request): JsonResponse
    {
        try {
            $bookingStoreDTO = new BookingStoreDTO($request);
            $authenticatedUserDTO = new AuthenticatedUserDTO($request->__authenticatedUser);

            if (!$authenticatedUserDTO->getUser()) {
                throw new Exception("User not logged in!");
            }

            $response = $this->bookingService->saveBooking($bookingStoreDTO, $authenticatedUserDTO);
            return $this->successResponse($response);
        } catch (Exception $e) {
            return $this->exceptionToResponse($e);
        }
    }

    /**
     * @param int $id
     * @param Request $request
     * @return Response
     */
    public function update(int $id, Request $request): Response
    {
        $data = $request->all();
        $cuser = $request->__authenticatedUser;
        $response = $this->repository->updateJob($id, array_except($data, ['_token', 'submit']), $cuser);
        return response($response);
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function immediateJobEmail(Request $request)
    {
        $adminSenderEmail = config('app.adminemail');
        $data = $request->all();

        $response = $this->repository->storeJobEmail($data);

        return response($response);
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function getHistory(Request $request)
    {
        if ($user_id = $request->get('user_id')) {

            $response = $this->repository->getUsersJobsHistory($user_id, $request);
            return response($response);
        }

        return null;
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function acceptJob(Request $request)
    {
        $data = $request->all();
        $user = $request->__authenticatedUser;

        $response = $this->repository->acceptJob($data, $user);

        return response($response);
    }

    public function acceptJobWithId(Request $request)
    {
        $data = $request->get('job_id');
        $user = $request->__authenticatedUser;

        $response = $this->repository->acceptJobWithId($data, $user);

        return response($response);
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function cancelJob(Request $request)
    {
        $data = $request->all();
        $user = $request->__authenticatedUser;

        $response = $this->repository->cancelJobAjax($data, $user);

        return response($response);
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function endJob(Request $request)
    {
        $data = $request->all();

        $response = $this->repository->endJob($data);

        return response($response);

    }

    public function customerNotCall(Request $request)
    {
        $data = $request->all();

        $response = $this->repository->customerNotCall($data);

        return response($response);

    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function getPotentialJobs(Request $request)
    {
        $data = $request->all();
        $user = $request->__authenticatedUser;

        $response = $this->repository->getPotentialJobs($user);

        return response($response);
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function distanceFeed(Request $request): Response
    {
        $data = $request->all();

        $distance = $data['distance'] ?? '';
        $time = $data['time'] ?? '';
        $jobid = $data['jobid'] ?? '';
        $session = $data['session_time'] ?? '';

        $flagged = $data['flagged'] == 'true' ? 'yes' : 'no';
        $manually_handled = $data['manually_handled'] == 'true' ? 'yes' : 'no';
        $by_admin = $data['by_admin'] == 'true' ? 'yes' : 'no';

        $admincomment = $data['admincomment'] ?? '';

        if ($time || $distance) {
            Distance::where('job_id', '=', $jobid)->update(['distance' => $distance, 'time' => $time]);
        }

        if ($admincomment || $session || $flagged || $manually_handled || $by_admin) {
            Job::where('id', '=', $jobid)->update([
                'admin_comments' => $admincomment,
                'flagged' => $flagged,
                'session_time' => $session,
                'manually_handled' => $manually_handled,
                'by_admin' => $by_admin
            ]);
        }

        return response('Record updated!');
    }

    public function reopen(Request $request)
    {
        $data = $request->all();
        $response = $this->repository->reopen($data);

        return response($response);
    }

    public function resendNotifications(Request $request)
    {
        $data = $request->all();
        $job = $this->repository->find($data['jobid']);
        $job_data = $this->repository->jobToData($job);
        $this->repository->sendNotificationTranslator($job, $job_data, '*');

        return response(['success' => 'Push sent']);
    }

    /**
     * Sends SMS to Translator
     * @param Request $request
     * @return ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function resendSMSNotifications(Request $request)
    {
        $data = $request->all();
        $job = $this->repository->find($data['jobid']);
        $job_data = $this->repository->jobToData($job);

        try {
            $this->repository->sendSMSNotificationToTranslator($job);
            return response(['success' => 'SMS sent']);
        } catch (Exception $e) {
            return response(['success' => $e->getMessage()]);
        }
    }
}
