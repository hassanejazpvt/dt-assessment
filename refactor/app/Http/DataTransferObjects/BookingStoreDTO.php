<?php

namespace DTApi\Http\DataTransferObjects;

use DTApi\Models\User;
use Illuminate\Http\Request;


class BookingStoreDTO extends BaseDTO
{
    private ?string $customerPhoneType;
    private ?string $customerPhysicalType;
    private ?bool $immediate;
    private ?array $jobFor;
    private ?string $bCreatedAt;
    private ?bool $byAdmin;

    /**
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        $this->setCustomerPhoneType($request->input('customer_phone_type'))
            ->setCustomerPhysicalType($request->input('customer_physical_type'))
            ->setImmediate($request->input('immediate'))
            ->setJobFor((array)$request->input('job_for'))
            ->setBCreatedAt($request->input('b_created_at'))
            ->setByAdmin($request->input('by_admin'));
    }

    /**
     * @param string|null $byAdmin
     * @return self
     */
    public function setByAdmin(?string $byAdmin): self
    {
        return $this->setAttribute('byAdmin', ($byAdmin == 'yes' || $byAdmin == true || $byAdmin == 1));
    }

    /**
     * @return bool|null
     */
    public function getByAdmin(): ?bool
    {
        return $this->byAdmin;
    }

    /**
     * @param string|null $bCreatedAt
     * @return self
     */
    public function setBCreatedAt(?string $bCreatedAt): self
    {
        return $this->setAttribute('bCreatedAt', $bCreatedAt);
    }

    /**
     * @return string|null
     */
    public function getBCreatedAt(): ?string
    {
        return $this->bCreatedAt;
    }

    /**
     * @param array|null $jobFor
     * @return self
     */
    public function setJobFor(?array $jobFor): self
    {
        return $this->setAttribute('jobFor', $jobFor);
    }

    /**
     * @return array|null
     */
    public function getJobFor(): ?array
    {
        return $this->jobFor;
    }

    /**
     * @param string|null $customerPhoneType
     * @return self
     */
    public function setCustomerPhoneType(?string $customerPhoneType): self
    {
        return $this->setAttribute('customerPhoneType', $customerPhoneType);
    }

    /**
     * @return string|null
     */
    public function getCustomerPhoneType(): ?string
    {
        return $this->customerPhoneType;
    }

    /**
     * @param string|null $customerPhysicalType
     * @return self
     */
    public function setCustomerPhysicalType(?string $customerPhysicalType): self
    {
        return $this->setAttribute('customerPhysicalType', $customerPhysicalType);
    }

    /**
     * @return string|null
     */
    public function getCustomerPhysicalType(): ?string
    {
        return $this->customerPhysicalType;
    }

    /**
     * @param string|null $immediate
     * @return self
     */
    public function setImmediate(?string $immediate): self
    {
        return $this->setAttribute('immediate', $immediate == 'yes');
    }

    /**
     * @return bool|null
     */
    public function getImmediate(): ?bool
    {
        return $this->immediate;
    }
}