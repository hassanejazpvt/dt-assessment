<?php

namespace DTApi\Service;

use DTApi\Http\DataTransferObjects\AuthenticatedUserDTO;
use DTApi\Http\DataTransferObjects\BookingDTO;
use DTApi\Repository\JobRepository;
use Illuminate\Database\Eloquent\Collection;

class JobService extends BaseService
{
    /**
     * @param JobRepository $repository
     */
    public function __construct(JobRepository $repository)
    {
        parent::__construct($repository);
    }

    /**
     * @param int $userId
     * @return array
     */
    public function getTranslatorJobs(int $userId): array
    {
        return $this->repository->getTranslatorJobs($userId);
    }

    /**
     * @param int $userId
     * @param array $job
     * @return mixed
     */
    public function checkParticularJob(int $userId, array $job)
    {
        return $this->repository->checkParticularJob($userId, $job);
    }

    /**
     * @param array $jobs
     * @param int $userId
     * @return array
     */
    public function updateUserCheck(array $jobs, int $userId): array
    {
        return collect($jobs)->each(function ($job, $key) use ($userId) {
            $job['user_check'] = $this->checkParticularJob($userId, $job);
        })->sortBy('due')->all();
    }

    /**
     * @param BookingDTO $bookingDTO
     * @param AuthenticatedUserDTO $authenticatedUserDTO
     * @return array
     */
    public function getAllJobs(BookingDTO $bookingDTO, AuthenticatedUserDTO $authenticatedUserDTO): array
    {
        if ($authenticatedUserDTO->getUser() && $authenticatedUserDTO->getUserType() == config('admin.SUPERADMIN_ROLE_ID')) {
            return $this->getAllJobsForSuperAdmin($bookingDTO);
        } else {
            return $this->getAllJobsForNonSuperAdmin($bookingDTO);
        }
    }

    /**
     * @param BookingDTO $bookingDTO
     * @return array
     */
    public function getAllJobsForSuperAdmin(BookingDTO $bookingDTO): array
    {
        return $this->repository->getAllJobsForSuperAdmin($bookingDTO);
    }

    /**
     * @param BookingDTO $bookingDTO
     * @return array
     */
    public function getAllJobsForNonSuperAdmin(BookingDTO $bookingDTO): array
    {
        return $this->repository->getAllJobsForNonSuperAdmin($bookingDTO);
    }

    /**
     * @param int $jobId
     * @return array|null
     */
    public function getJob(int $jobId): ?array
    {
        return $this->repository->getJob($jobId);
    }
}