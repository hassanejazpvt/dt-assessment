<?php

namespace DTApi\Http\DataTransferObjects;

use DTApi\Models\User;

class AuthenticatedUserDTO extends BaseDTO
{
    private ?string $userType;
    private ?User $user;
    private ?UserMetaDTO $userMeta;

    /**
     * @param User|null $user
     */
    public function __construct(?User $user = null)
    {
        $this->setUser($user)
            ->setUserMeta($user->userMeta ?: null)
            ->setUserType($user->user_type ?: null);
    }

    /**
     * @param object|null $userMeta
     * @return self
     */
    public function setUserMeta(?object $userMeta = null): self
    {
        return $this->setAttribute('userMeta', new UserMetaDTO($userMeta));
    }

    /**
     * @return UserMetaDTO|null
     */
    public function getUserMeta(): ?UserMetaDTO
    {
        return $this->userMeta;
    }

    /**
     * @return User|null
     */
    public function getUser(): ?User
    {
        return $this->user;
    }

    /**
     * @param User|null $user
     * @return $this
     */
    public function setUser(?User $user): self
    {
        return $this->setAttribute('user', $user);
    }

    /**
     * @return string|null
     */
    public function getUserType(): ?string
    {
        return $this->userType;
    }

    /**
     * @param string|null $userType
     * @return $this
     */
    public function setUserType(?string $userType): self
    {
        return $this->setAttribute('userType', $userType);
    }
}