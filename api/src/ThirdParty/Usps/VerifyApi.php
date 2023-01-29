<?php

namespace App\AddressNormalizer\ThirdParty\Usps;

use App\AddressNormalizer\Dto\AddressDto;
use App\AddressNormalizer\ThirdParty\Exceptions\ThirdPartyException;

class VerifyApi extends AbstractBase implements VerifyApiInterface
{
    public function request(AddressDto $address): AddressDto
    {
        $xml = $this->callApi(ApiNames::Verify, $this->getXml($address));

        if ($error = $xml->Address->Error) {
            $this->throwError(ApiNames::Verify, $error);
        }

        $normalizedAddress = $xml->Address;

        return new AddressDto(
            $normalizedAddress->Address1,
            $normalizedAddress->Address2,
            $normalizedAddress->City,
            $normalizedAddress->State,
            $normalizedAddress->Zip5,
        );
    }

    private function getXml(AddressDto $address): string
    {
        $useZip5 = str_contains($address->zipCode, '-');

        $xml = new \SimpleXMLElement('<AddressValidateRequest></AddressValidateRequest>');
        $xml->addAttribute('USERID', $this->getUsername());
        $xml->addChild('Revision', '1');
        $addresses = $xml->addChild('Address');
        $addresses->addAttribute('ID', '0');
        $addresses->addChild('Address1', $address->line1);
        $addresses->addChild('Address2', $address->line2);
        $addresses->addChild('City', $address->city);
        $addresses->addChild('State', $address->state);
        $addresses->addChild('Zip5', $useZip5 ? $address->zipCode : '');
        $addresses->addChild('Zip4', !$useZip5 ? $address->zipCode : '');

        return $xml->asXML();
    }
}
