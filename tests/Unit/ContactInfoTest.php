<?php

namespace Test\Unit;


use Connect\ContactInfo;
use Connect\Contact;
use Connect\PhoneNumber;

class ContactInfoTest extends \Test\TestCase
{
    public function testInstantiation()
    {
        $phoneNumber = new PhoneNumber([
            "country_code" => "+1",
            "area_code" => "0",
            "phone_number" => "1234",
            "extension" => "1"
        ]);
        $contact = new Contact([
            'email' => "null@null.com",
            "first_name" => "First",
            "last_name" => "last",
            "phone_number" => $phoneNumber
        ]);
        $contactInfo = new ContactInfo([
            'address_line1' => "some address",
            'address_line2' => "some address 2",
            'city' => "City",
            "country" => "Country",
            "postal_code" => "1234",
            "state" => "state",
            "contact" => $contact
        ]);

        $this->assertInstanceOf('\Connect\ContactInfo', $contactInfo);

        $this->assertEquals("some address", $contactInfo->address_line1);
        $this->assertEquals('some address 2', $contactInfo->address_line2);
        $this->assertEquals("City", $contactInfo->city);
        $this->assertEquals("Country", $contactInfo->country);
        $this->assertEquals("1234", $contactInfo->postal_code);
        $this->assertEquals("state", $contactInfo->state);
        $this->assertInstanceOf('\Connect\Contact', $contactInfo->contact);
        $this->assertInstanceOf('\Connect\PhoneNumber', $contactInfo->contact->phone_number);
    }

}