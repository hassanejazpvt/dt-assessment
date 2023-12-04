<?php

namespace DTApi\Http\Enums;

class BookingEnum
{
    const TYPE_IMMEDIATE = 'immediate';
    const TYPE_REGULAR = 'regular';

    const YES = 'yes';
    const NO = 'no';

    const GENDER_MALE = 'male';
    const GENDER_FEMALE = 'female';

    const JOB_FOR_NORMAL = 'normal';
    const JOB_FOR_CERTIFIED = 'certified';
    const JOB_FOR_CERTIFIED_IN_LAW = 'certified_in_law';
    const JOB_FOR_CERTIFIED_IN_HEALTH = 'certified_in_health';
    const JOB_FOR_MAN = 'Man';
    const JOB_FOR_KVINNA = 'Kvinna';

    const CERTIFIED_NORMAL = 'normal';
    const CERTIFIED_YES = 'yes';
    const CERTIFIED_LAW = 'law';
    const CERTIFIED_HEALTH = 'health';
    const CERTIFIED_BOTH = 'both';
    const CERTIFIED_N_LAW = 'n_law';
    const CERTIFIED_N_HEALTH = 'n_health';
}