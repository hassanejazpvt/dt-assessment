<?php

namespace DTApi\Repository;

use DTApi\Http\Enums\UserEnum;
use DTApi\Models\User;
use Illuminate\Database\Eloquent\Collection;

class UserRepository extends BaseRepository
{
    /**
     * @param User $model
     */
    function __construct(User $model)
    {
        parent::__construct($model);
    }

    /**
     * @param int $userId
     * @return array
     */
    public function getUserJobs(int $userId): array
    {
        $user = $this->find($userId);
        $jobs = [];
        if ($user) {
            $jobs = $user->jobs()
                ->with('user.userMeta', 'user.average', 'translatorJobRel.user.average', 'language', 'feedback')
                ->whereIn('status', [UserEnum::STATUS_PENDING, UserEnum::STATUS_ASSIGNED, UserEnum::STATUS_STARTED])
                ->orderBy('due', 'asc')
                ->get()
                ->toArray();
        }

        return $jobs;
    }

    /**
     * @param array $emails
     * @return Collection|null
     */
    public function getUsersByEmails(array $emails): ?Collection
    {
        return $this->model->whereIn('email', $emails)->get();
    }

    /**
     * @param string $email
     * @return User|null
     */
    public function getUserByEmail(string $email): ?User
    {
        return $this->model->where('email', $email)->first();
    }
}