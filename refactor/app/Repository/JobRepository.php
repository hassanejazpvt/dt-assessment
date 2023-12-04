<?php

namespace DTApi\Repository;

use DTApi\Http\DataTransferObjects\BookingDTO;
use DTApi\Http\Enums\JobEnum;
use DTApi\Models\Job;
use DTApi\Service\UserService;
use Illuminate\Database\Eloquent\Collection;

class JobRepository extends BaseRepository
{
    /**
     * @param Job $model
     */
    function __construct(Job $model)
    {
        parent::__construct($model);
    }

    /**
     * @param int $userId
     * @return array
     */
    public function getTranslatorJobs(int $userId): array
    {
        $data = Job::getTranslatorJobs($userId, JobEnum::STATUS_NEW);
        return $data->pluck('jobs')->all();
    }

    /**
     * @param int $userId
     * @param array $job
     * @return mixed
     */
    public function checkParticularJob(int $userId, array $job)
    {
        return Job::checkParticularJob($userId, $job);
    }

    /**
     * @param BookingDTO $bookingDTO
     * @return array
     */
    public function getAllJobsForNonSuperAdmin(BookingDTO $bookingDTO): array
    {
        $jobs = $this->query();

        /**
         * @var UserService $userService
         */
        $userService = app(UserService::class);

        if ($ids = $bookingDTO->getIds()) {
            $jobs->whereIn('id', $ids);
        }

        if ($bookingDTO->getConsumerType() == strtoupper(JobEnum::CONSUMER_TYPE_RWS)) {
            $jobs->where('job_type', '=', JobEnum::CONSUMER_TYPE_RWS);
        } else {
            $jobs->where('job_type', '=', JobEnum::CONSUMER_TYPE_UNPAID);
        }

        if ($bookingDTO->getFeedback()) {
            $jobs->where('ignore_feedback', '0');
            $jobs->whereHas('feedback', function ($q) {
                $q->where('rating', '<=', '3');
            });
            if ($bookingDTO->getCount())
                return ['count' => $jobs->count()];
        }

        if ($lang = $bookingDTO->getLang()) {
            $jobs->whereIn('from_language_id', $lang);
        }

        if ($status = $bookingDTO->getStatus()) {
            $jobs->whereIn('status', $status);
        }

        if ($jobType = $bookingDTO->getJobType()) {
            $jobs->whereIn('job_type', $jobType);
        }

        if ($customerEmails = $bookingDTO->getCustomerEmails()) {
            $user = $userService->getUserByEmail($customerEmails[0]);
            if ($user) {
                $jobs->where('user_id', '=', $user->id);
            }
        }

        if ($bookingDTO->getFilterTimetype() == JobEnum::FILTER_TIMETYPE_CREATED) {
            if ($from = $bookingDTO->getFrom()) {
                $jobs->where('created_at', '>=', $from);
            }

            if ($to = $bookingDTO->getTo()) {
                $to = "{$to} 23:59:00";
                $jobs->where('created_at', '<=', $to);
            }
            $jobs->orderBy('created_at', 'desc');
        }

        if ($bookingDTO->getFilterTimetype() == JobEnum::FILTER_TIMETYPE_DUE) {
            if ($from = $bookingDTO->getFrom()) {
                $jobs->where('due', '>=', $from);
            }
            if ($to = $bookingDTO->getTo()) {
                $to = "{$to} 23:59:00";
                $jobs->where('due', '<=', $to);
            }
            $jobs->orderBy('due', 'desc');
        }

        $jobs->orderBy('created_at', 'desc');
        $jobs->with('user', 'language', 'feedback.user', 'translatorJobRel.user', 'distance');
        if ($bookingDTO->getLimit() == 'all')
            $jobs = $jobs->get();
        else
            $jobs = $jobs->paginate(15);

        return $jobs->toArray();
    }

    /**
     * @param BookingDTO $bookingDTO
     * @return array
     */
    public function getAllJobsForSuperAdmin(BookingDTO $bookingDTO): array
    {
        $jobs = $this->query();

        /**
         * @var UserService $userService
         */
        $userService = app(UserService::class);

        if ($bookingDTO->getFeedback()) {
            $jobs->where('ignore_feedback', '0');
            $jobs->whereHas('feedback', function ($q) {
                $q->where('rating', '<=', '3');
            });
            if ($bookingDTO->getCount())
                return ['count' => $jobs->count()];
        }

        if ($ids = $bookingDTO->getIds()) {
            $jobs->whereIn('id', $ids);
        }

        if ($lang = $bookingDTO->getLang()) {
            $jobs->whereIn('from_language_id', $lang);
        }

        if ($status = $bookingDTO->getStatus()) {
            $jobs->whereIn('status', $status);
        }

        if ($expiredAt = $bookingDTO->getExpiredAt()) {
            $jobs->where('expired_at', '>=', $expiredAt);
        }

        if ($willExpireAt = $bookingDTO->getWillExpireAt()) {
            $jobs->where('will_expire_at', '>=', $willExpireAt);
        }

        if ($customerEmails = $bookingDTO->getCustomerEmails()) {
            $users = $userService->getUsersByEmails($customerEmails);
            if ($users) {
                $jobs->whereIn('user_id', collect($users)->pluck('id')->all());
            }
        }

        if ($translatorEmails = $bookingDTO->getTranslatorEmails()) {
            $users = $userService->getUsersByEmails($translatorEmails);
            if ($users) {
                $allJobIDs = DB::table('translator_job_rel')->whereNull('cancel_at')->whereIn('user_id', collect($users)->pluck('id')->all())->lists('job_id');
                $jobs->whereIn('id', $allJobIDs);
            }
        }
        if ($bookingDTO->getFilterTimetype() == JobEnum::FILTER_TIMETYPE_CREATED) {
            if ($from = $bookingDTO->getFrom()) {
                $jobs->where('created_at', '>=', $from);
            }
            if ($to = $bookingDTO->getTo()) {
                $to = "{$to} 23:59:00";
                $jobs->where('created_at', '<=', $to);
            }
            $jobs->orderBy('created_at', 'desc');
        }

        if ($bookingDTO->getFilterTimetype() == JobEnum::FILTER_TIMETYPE_DUE) {
            if ($from = $bookingDTO->getFrom()) {
                $jobs->where('due', '>=', $from);
            }
            if ($to = $bookingDTO->getTo()) {
                $to = "{$to} 23:59:00";
                $jobs->where('due', '<=', $to);
            }
            $jobs->orderBy('due', 'desc');
        }

        if ($jobType = $bookingDTO->getJobType()) {
            $jobs->whereIn('job_type', $jobType);
        }

        if ($physical = $bookingDTO->getPhysical()) {
            $jobs->where('customer_physical_type', $physical);
            $jobs->where('ignore_physical', 0);
        }

        if ($phone = $bookingDTO->getPhone()) {
            $jobs->where('customer_phone_type', $phone);
            if ($bookingDTO->getPhysical())
                $jobs->where('ignore_physical_phone', 0);
        }

        if ($flagged = $bookingDTO->getFlagged()) {
            $jobs->where('flagged', $flagged);
            $jobs->where('ignore_flagged', 0);
        }

        if ($bookingDTO->getDistance() == JobEnum::DISTANCE_EMPTY) {
            $jobs->whereDoesntHave('distance');
        }

        if ($bookingDTO->getSalary() == JobEnum::YES) {
            $jobs->whereDoesntHave('user.salaries');
        }

        if ($bookingDTO->getCount()) {
            return ['count' => $jobs->count()];
        }

        if ($consumerType = $bookingDTO->getConsumerType()) {
            $jobs->whereHas('user.userMeta', function ($q) use ($consumerType) {
                $q->where('consumer_type', $consumerType);
            });
        }

        if ($bookingType = $bookingDTO->getBookingType()) {
            if ($bookingType == JobEnum::BOOKING_TYPE_PHYSICAL)
                $jobs->where('customer_physical_type', JobEnum::YES);
            if ($bookingType == JobEnum::BOOKING_TYPE_PHONE)
                $jobs->where('customer_phone_type', JobEnum::YES);
        }

        $jobs->orderBy('created_at', 'desc');
        $jobs->with('user', 'language', 'feedback.user', 'translatorJobRel.user', 'distance');
        if ($bookingDTO->getLimit() == 'all')
            $jobs = $jobs->get();
        else
            $jobs = $jobs->paginate(15);

        return $jobs->toArray();
    }

    /**
     * @param int $jobId
     * @return array|null
     */
    public function getJob(int $jobId): ?array
    {
        return $this->model->with('translatorJobRel.user')->find($jobId)->toArray();
    }
}