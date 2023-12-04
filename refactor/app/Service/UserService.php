<?php

namespace DTApi\Service;

use DTApi\Models\User;
use DTApi\Repository\UserRepository;
use Illuminate\Database\Eloquent\Collection;


class UserService extends BaseService
{
    /**
     * @param UserRepository $repository
     */
    public function __construct(UserRepository $repository)
    {
        parent::__construct($repository);
    }

    /**
     * @param int $userId
     * @return array
     */
    public function getUserJobs(int $userId): array
    {
        return $this->repository->getUserJobs($userId);
    }

    /**
     * @param array $emails
     * @return Collection|null
     */
    public function getUsersByEmails(array $emails): ?Collection
    {
        return $this->repository->getUsersByEmails($emails);
    }

    /**
     * @param string $email
     * @return User|null
     */
    public function getUserByEmail(string $email): ?User
    {
        return $this->repository->getUserByEmail($email);
    }
}