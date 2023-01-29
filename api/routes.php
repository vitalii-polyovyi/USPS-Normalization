<?php

use App\AddressNormalizer\Api\Actions\NormalizeAddressAction;
use App\AddressNormalizer\Api\Actions\SaveAddressAction;

return [
    '/address/normalize' => ['POST', NormalizeAddressAction::class],
    '/address/save' => ['POST', SaveAddressAction::class],
];
