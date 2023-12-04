<?php

namespace DTApi\Http\DataTransferObjects;

use DTApi\Models\User;

class UserMetaDTO extends BaseDTO
{
    private ?string $consumerType;
    private ?string $city;
    private ?string $customerType;

    /**
     * @param object|null $userMeta
     */
    public function __construct(?object $userMeta = null)
    {
        $this->setConsumerType($userMeta->consumer_type ?: null)
            ->setCity($userMeta->city ?: null)
            ->setCustomerType($userMeta->customer_type ?: null);
    }

    /**
     * @param string|null $customerType
     * @return self
     */
    public function setCustomerType(?string $customerType): self
    {
        return $this->setAttribute('customerType', $customerType);
    }

    /**
     * @return string|null
     */
    public function getCustomerType(): ?string
    {
        return $this->customerType;
    }

    /**
     * @param string|null $city
     * @return self
     */
    public function setCity(?string $city): self
    {
        return $this->setAttribute('city', $city);
    }

    /**
     * @return string|null
     */
    public function getCity(): ?string
    {
        return $this->city;
    }

    /**
     * @param string|null $consumerType
     * @return self
     */
    public function setConsumerType(?string $consumerType): self
    {
        return $this->setAttribute('consumerType', $consumerType);
    }

    /**
     * @return string|null
     */
    public function getConsumerType(): ?string
    {
        return $this->consumerType;
    }
}