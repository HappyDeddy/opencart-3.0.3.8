<?php

namespace GlobalPayments\Api\Entities\Enums;

use GlobalPayments\Api\Entities\Enum;

class TransactionType extends Enum
{
    const DECLINE = 1;
    const VERIFY = 2;
    const CAPTURE = 4;
    const AUTH = 8;
    const REFUND = 16;
    const REVERSAL = 32;
    const SALE =64;
    const EDIT = 128;
    const VOID = 256;
    const ADD_VALUE = 512;
    const BALANCE = 1024;
    const ACTIVATE = 2048;
    const ALIAS = 4096;
    const REPLACE = 8192;
    const REWARD = 16384;
    const DEACTIVATE = 32768;
    const BATCH_CLOSE = 65536;
    const CREATE = 131072;
    const DELETE = 262144;
    const FETCH = 524288;
    const SEARCH = 1048576;
    const HOLD = 2097152;
    const RELEASE = 4194304;
    const DCC_RATE_LOOKUP = 8388608;
    const VERIFY_ENROLLED = 16777216;
    const VERIFY_SIGNATURE = 33554432;
    const TOKEN_DELETE = 67108864;
    const VERIFY_AUTHENTICATION = 134217728;
    const INITIATE_AUTHENTICATION = 268435456;
    const DATA_COLLECT = 536870912;
    const PRE_AUTH_COMPLETION = 1073741824;
    const TOKEN_UPDATE = 2147483648;
}
