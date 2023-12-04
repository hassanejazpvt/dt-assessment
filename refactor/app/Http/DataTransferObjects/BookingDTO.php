<?php

namespace DTApi\Http\DataTransferObjects;

use Illuminate\Http\Request;

class BookingDTO extends BaseDTO
{
    private ?int $userId;
    private ?bool $feedback;
    private ?bool $count;
    private ?array $ids;
    private ?int $lang;
    private ?string $status;
    private ?string $expiredAt;
    private ?string $willExpireAt;
    private ?array $customerEmails;
    private ?array $translatorEmails;
    private ?string $filterTimetype;
    private ?string $to;
    private ?string $from;
    private ?string $jobType;
    private ?string $physical;
    private ?string $phone;
    private ?string $flagged;
    private ?string $distance;
    private ?string $salary;
    private ?string $consumerType;
    private ?string $bookingType;
    private ?string $limit;

    /**
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        $this->setUserId($request->input('user_id'))
            ->setFeedback($request->input('feedback'))
            ->setCount($request->input('count'))
            ->setIds((array)$request->input('id'))
            ->setLang($request->input('lang'))
            ->setStatus($request->input('status'))
            ->setExpiredAt($request->input('expired_at'))
            ->setWillExpireAt($request->input('will_expire_at'))
            ->setCustomerEmails((array)$request->input('customer_email'))
            ->setTranslatorEmails((array)$request->input('translator_email'))
            ->setFilterTimetype($request->input('filter_timetype'))
            ->setTo($request->input('to'))
            ->setFrom($request->input('from'))
            ->setJobType($request->input('job_type'))
            ->setPhysical($request->input('physical'))
            ->setPhone($request->input('phone'))
            ->setFlagged($request->input('flagged'))
            ->setDistance($request->input('distance'))
            ->setSalary($request->input('salary'))
            ->setConsumerType($request->input('consumer_type'))
            ->setBookingType($request->input('booking_type'))
            ->setLimit($request->input('limit'));
    }

    /**
     * @param string|null $limit
     * @return self
     */
    public function setLimit(?string $limit): self
    {
        return $this->setAttribute('limit', $limit);
    }

    /**
     * @return string|null
     */
    public function getLimit(): ?string
    {
        return $this->limit;
    }

    /**
     * @param string|null $expiredAt
     * @return self
     */
    public function setExpiredAt(?string $expiredAt): self
    {
        return $this->setAttribute('expiredAt', $expiredAt);
    }

    /**
     * @return string|null
     */
    public function getExpiredAt(): ?string
    {
        return $this->expiredAt;
    }

    /**
     * @param string|null $willExpireAt
     * @return self
     */
    public function setWillExpireAt(?string $willExpireAt): self
    {
        return $this->setAttribute('willExpireAt', $willExpireAt);
    }

    /**
     * @return string|null
     */
    public function getWillExpireAt(): ?string
    {
        return $this->willExpireAt;
    }

    /**
     * @param array|null $customerEmails
     * @return self
     */
    public function setCustomerEmails(?array $customerEmails): self
    {
        return $this->setAttribute('customerEmails', $customerEmails);
    }

    /**
     * @return array|null
     */
    public function getCustomerEmails(): ?array
    {
        return $this->customerEmails;
    }

    /**
     * @param array|null $translatorEmails
     * @return self
     */
    public function setTranslatorEmails(?array $translatorEmails): self
    {
        return $this->setAttribute('translatorEmails', $translatorEmails);
    }

    /**
     * @return array|null
     */
    public function getTranslatorEmails(): ?array
    {
        return $this->translatorEmails;
    }

    /**
     * @param string|null $filterTimetype
     * @return self
     */
    public function setFilterTimetype(?string $filterTimetype): self
    {
        return $this->setAttribute('filterTimetype', $filterTimetype);
    }

    /**
     * @return string|null
     */
    public function getFilterTimetype(): ?string
    {
        return $this->filterTimetype;
    }

    /**
     * @param string|null $to
     * @return self
     */
    public function setTo(?string $to): self
    {
        return $this->setAttribute('to', $to);
    }

    /**
     * @return string|null
     */
    public function getTo(): ?string
    {
        return $this->to;
    }

    /**
     * @param string|null $from
     * @return self
     */
    public function setFrom(?string $from): self
    {
        return $this->setAttribute('from', $from);
    }

    /**
     * @return string|null
     */
    public function getFrom(): ?string
    {
        return $this->from;
    }

    /**
     * @param string|null $jobType
     * @return self
     */
    public function setJobType(?string $jobType): self
    {
        return $this->setAttribute('jobType', $jobType);
    }

    /**
     * @return string|null
     */
    public function getJobType(): ?string
    {
        return $this->jobType;
    }

    /**
     * @param string|null $physical
     * @return self
     */
    public function setPhysical(?string $physical): self
    {
        return $this->setAttribute('physical', $physical);
    }

    /**
     * @return string|null
     */
    public function getPhysical(): ?string
    {
        return $this->physical;
    }

    /**
     * @param string|null $phone
     * @return self
     */
    public function setPhone(?string $phone): self
    {
        return $this->setAttribute('phone', $phone);
    }

    /**
     * @return string|null
     */
    public function getPhone(): ?string
    {
        return $this->phone;
    }

    /**
     * @param string|null $flagged
     * @return self
     */
    public function setFlagged(?string $flagged): self
    {
        return $this->setAttribute('flagged', $flagged);
    }

    /**
     * @return string|null
     */
    public function getFlagged(): ?string
    {
        return $this->flagged;
    }

    /**
     * @param string|null $distance
     * @return self
     */
    public function setDistance(?string $distance): self
    {
        return $this->setAttribute('distance', $distance);
    }

    /**
     * @return string|null
     */
    public function getDistance(): ?string
    {
        return $this->distance;
    }

    /**
     * @param string|null $salary
     * @return self
     */
    public function setSalary(?string $salary): self
    {
        return $this->setAttribute('salary', $salary);
    }

    /**
     * @return string|null
     */
    public function getSalary(): ?string
    {
        return $this->salary;
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

    /**
     * @param string|null $bookingType
     * @return self
     */
    public function setBookingType(?string $bookingType): self
    {
        return $this->setAttribute('bookingType', $bookingType);
    }

    /**
     * @return string|null
     */
    public function getBookingType(): ?string
    {
        return $this->bookingType;
    }

    /**
     * @param string|null $status
     * @return self
     */
    public function setStatus(?string $status): self
    {
        return $this->setAttribute('status', $status);
    }

    /**
     * @return string|null
     */
    public function getStatus(): ?string
    {
        return $this->status;
    }

    /**
     * @param string|null $lang
     * @return self
     */
    public function setLang(?string $lang): self
    {
        return $this->setAttribute('lang', $lang);
    }

    /**
     * @return int|null
     */
    public function getLang(): ?int
    {
        return $this->lang;
    }

    /**
     * @param array|null $ids
     * @return $this
     */
    public function setIds(?array $ids): self
    {
        return $this->setAttribute('ids', $ids);
    }

    /**
     * @return array|null
     */
    public function getIds(): ?array
    {
        return $this->ids;
    }

    /**
     * @return int|null
     */
    public function getUserId(): ?int
    {
        return $this->userId;
    }

    /**
     * @param int|null $userId
     * @return $this
     */
    public function setUserId(?int $userId): self
    {
        return $this->setAttribute('userId', $userId);
    }

    /**
     * @param string|null $feedback
     * @return $this
     */
    public function setFeedback(?string $feedback): self
    {
        return $this->setAttribute('feedback', ($feedback == 'true' || $feedback == true));
    }

    /**
     * @return bool|null
     */
    public function getFeedback(): ?bool
    {
        return $this->feedback;
    }

    /**
     * @param string|null $count
     * @return $this
     */
    public function setCount(?string $count): self
    {
        return $this->setAttribute('count', ($count == 'true' || $count == true));
    }

    /**
     * @return bool
     */
    public function getCount(): bool
    {
        return $this->count;
    }
}