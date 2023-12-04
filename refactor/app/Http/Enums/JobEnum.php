<?php

namespace DTApi\Http\Enums;

class JobEnum
{
    const STATUS_NEW = 'new';

    const YES = 'yes';

    const FILTER_TIMETYPE_CREATED = 'created';
    const FILTER_TIMETYPE_DUE = 'due';

    const DISTANCE_EMPTY = 'empty';

    const BOOKING_TYPE_PHYSICAL = 'physical';
    const BOOKING_TYPE_PHONE = 'phone';

    const CONSUMER_TYPE_RWS = 'rws';
    const CONSUMER_TYPE_RWSCONSUMER = 'rwsconsumer';
    const CONSUMER_TYPE_UNPAID = 'unpaid';
    const CONSUMER_TYPE_PAID = 'paid';
    const CONSUMER_TYPE_NGO = 'ngo';
}