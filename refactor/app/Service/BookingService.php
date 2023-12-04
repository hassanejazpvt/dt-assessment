<?php

namespace DTApi\Service;

use DTApi\Exceptions\ValidationHttpResponseException;
use DTApi\Helpers\TeHelper;
use DTApi\Http\DataTransferObjects\AuthenticatedUserDTO;
use DTApi\Http\DataTransferObjects\BookingDTO;
use DTApi\Http\DataTransferObjects\BookingStoreDTO;
use DTApi\Http\Enums\BookingEnum;
use DTApi\Http\Enums\JobEnum;
use DTApi\Repository\BookingRepository;
use Exception;

class BookingService extends BaseService
{
    /**
     * @param BookingRepository $repository
     */
    public function __construct(BookingRepository $repository)
    {
        parent::__construct($repository);
    }

    /**
     * @param BookingDTO $bookingDTO
     * @param AuthenticatedUserDTO $authenticatedUserDTO
     * @return array
     */
    public function getJobs(BookingDTO $bookingDTO, AuthenticatedUserDTO $authenticatedUserDTO): array
    {
        $response = [];
        if ($userId = $bookingDTO->getUserId()) {
            $response = $this->getUserJobs($userId);
        } else {
            # We should use config() instead of env() directly. We can create an admin.php inside config/ directory and put ADMIN_ROLE_ID & SUPERADMIN_ROLE_ID in it.
            # The admin.php file could look like following
            # return [
            #     'ADMIN_ROLE_ID' => env('ADMIN_ROLE_ID', DEFAULT_VALUE),
            #     'SUPERADMIN_ROLE_ID' => env('SUPERADMIN_ROLE_ID', DEFAULT_VALUE)
            # ];
            if (in_array($authenticatedUserDTO->getUserType(), [config('admin.ADMIN_ROLE_ID'), config('admin.SUPERADMIN_ROLE_ID')])) {
                $response = $this->getAllJobs($bookingDTO, $authenticatedUserDTO);
            }
        }

        return $response;
    }

    /**
     * @param int $userId
     * @return array
     */
    public function getUserJobs(int $userId): array
    {
        return $this->repository->getUsersJobs($userId);
    }

    /**
     * @param BookingDTO $bookingDTO
     * @param AuthenticatedUserDTO $authenticatedUserDTO
     * @return array
     */
    public function getAllJobs(BookingDTO $bookingDTO, AuthenticatedUserDTO $authenticatedUserDTO): array
    {
        return $this->repository->getAllJobs($bookingDTO, $authenticatedUserDTO);
    }

    /**
     * @param BookingStoreDTO $bookingStoreDTO
     * @param AuthenticatedUserDTO $authenticatedUserDTO
     * @return array
     * @throws Exception
     */
    public function saveBooking(BookingStoreDTO $bookingStoreDTO, AuthenticatedUserDTO $authenticatedUserDTO): array
    {
        $immediatetime = 5;

        $userMeta = $authenticatedUserDTO->getUserMeta();

        $response = [];
        $data = request()->all();

        if ($authenticatedUserDTO->getUserType() == config('customer.CUSTOMER_ROLE_ID')) {
            $data['customer_phone_type'] = $bookingStoreDTO->getCustomerPhoneType() ? BookingEnum::YES : BookingEnum::NO;
            $data['customer_physical_type'] = $response['customer_physical_type'] = $bookingStoreDTO->getCustomerPhysicalType() ? BookingEnum::YES : BookingEnum::NO;

            if ($bookingStoreDTO->getImmediate()) {
                $data['due'] = carbon()->now()->addMinute($immediatetime)->toDateTimeString();
                $data['immediate'] = $data['customer_phone_type'] = BookingEnum::YES;
                $response['type'] = BookingEnum::TYPE_IMMEDIATE;
            } else {
                $due = sprintf('%s %s', $data['due_date'], $data['due_time']);
                $response['type'] = BookingEnum::TYPE_REGULAR;
                $dueCarbon = carbon()->createFromFormat('m/d/Y H:i', $due);
                $data['due'] = $dueCarbon->format('Y-m-d H:i:s');
                if ($dueCarbon->isPast()) {
                    throw new Exception("Can't create booking in past");
                }
            }

            if (in_array(BookingEnum::GENDER_MALE, $bookingStoreDTO->getJobFor())) {
                $data['gender'] = BookingEnum::GENDER_MALE;
            } else if (in_array(BookingEnum::GENDER_FEMALE, $bookingStoreDTO->getJobFor())) {
                $data['gender'] = BookingEnum::GENDER_FEMALE;
            }

            if (in_array(BookingEnum::JOB_FOR_NORMAL, $bookingStoreDTO->getJobFor())) {
                $data['certified'] = BookingEnum::CERTIFIED_NORMAL;
            } else if (in_array(BookingEnum::JOB_FOR_CERTIFIED, $bookingStoreDTO->getJobFor())) {
                $data['certified'] = BookingEnum::CERTIFIED_YES;
            } else if (in_array(BookingEnum::JOB_FOR_CERTIFIED_IN_LAW, $bookingStoreDTO->getJobFor())) {
                $data['certified'] = BookingEnum::CERTIFIED_LAW;
            } else if (in_array(BookingEnum::JOB_FOR_CERTIFIED_IN_HEALTH, $bookingStoreDTO->getJobFor())) {
                $data['certified'] = BookingEnum::CERTIFIED_HEALTH;
            }

            if (in_array(BookingEnum::JOB_FOR_NORMAL, $bookingStoreDTO->getJobFor()) && in_array(BookingEnum::JOB_FOR_CERTIFIED, $bookingStoreDTO->getJobFor())) {
                $data['certified'] = BookingEnum::CERTIFIED_BOTH;
            } else if (in_array(BookingEnum::JOB_FOR_NORMAL, $bookingStoreDTO->getJobFor()) && in_array(BookingEnum::JOB_FOR_CERTIFIED_IN_LAW, $bookingStoreDTO->getJobFor())) {
                $data['certified'] = BookingEnum::CERTIFIED_N_LAW;
            } else if (in_array(BookingEnum::JOB_FOR_NORMAL, $bookingStoreDTO->getJobFor()) && in_array(BookingEnum::JOB_FOR_CERTIFIED_IN_HEALTH, $bookingStoreDTO->getJobFor())) {
                $data['certified'] = BookingEnum::CERTIFIED_N_HEALTH;
            }

            if ($userMeta->getConsumerType() == JobEnum::CONSUMER_TYPE_RWSCONSUMER)
                $data['job_type'] = JobEnum::CONSUMER_TYPE_RWS;
            else if ($userMeta->getConsumerType() == JobEnum::CONSUMER_TYPE_NGO)
                $data['job_type'] = JobEnum::CONSUMER_TYPE_UNPAID;
            else if ($userMeta->getConsumerType() == JobEnum::CONSUMER_TYPE_PAID)
                $data['job_type'] = JobEnum::CONSUMER_TYPE_PAID;

            $data['b_created_at'] = carbon()->now()->toDateTimeString();

            if (isset($due))
                $data['will_expire_at'] = TeHelper::willExpireAt($due, $bookingStoreDTO->getBCreatedAt());

            $data['by_admin'] = $bookingStoreDTO->getByAdmin() ? 'yes' : 'no';

            $job = $authenticatedUserDTO->getUser()->jobs()->create($data);

            $response['id'] = $job->id;

            $data['job_for'] = [];
            if ($job->gender) {
                if ($job->gender == BookingEnum::GENDER_MALE) {
                    $data['job_for'][] = BookingEnum::JOB_FOR_MAN;
                } else if ($job->gender == BookingEnum::GENDER_FEMALE) {
                    $data['job_for'][] = BookingEnum::JOB_FOR_KVINNA;
                }
            }

            if ($job->certified) {
                if ($job->certified == BookingEnum::CERTIFIED_BOTH) {
                    $data['job_for'][] = BookingEnum::JOB_FOR_NORMAL;
                    $data['job_for'][] = BookingEnum::JOB_FOR_CERTIFIED;
                } else if ($job->certified == BookingEnum::CERTIFIED_YES) {
                    $data['job_for'][] = BookingEnum::JOB_FOR_CERTIFIED;
                } else {
                    $data['job_for'][] = $job->certified;
                }
            }

            $data['customer_town'] = $userMeta->getCity();
            $data['customer_type'] = $userMeta->getCustomerType();

            //Event::fire(new JobWasCreated($job, $data, '*'));

//            $this->sendNotificationToSuitableTranslators($job->id, $data, '*');// send Push for New job posting
        } else {
            throw new Exception("Translator can not create booking");
        }

        return $response;
    }
}