<?php

namespace Paloma\Shop\Checkout;

use PHPUnit\Framework\TestCase;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Validator\RecursiveValidator;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class AddressTest extends TestCase
{
    public function testValidateEmpty()
    {
        $validation = $this->validator()->validate(Address::ofData([]));

        $this->assertEquals(1, $validation->count());
    }

    public function testValidateLengths()
    {
        $validation = $this->validator()->validate(Address::ofData([
            'tile' => '00000000000',
            'firstName' => '0000000000000000000000000000000',
            'lastName' => '0000000000000000000000000000000',
            'company' => '000000000000000000000000000000000000000000000000000',
            'street' => '000000000000000000000000000000000000000000000000000',
            'city' => '0000000000000000000000000000000',
            'zipCode' => '00000000000',
            'country' => '00000',
            'phoneNumber' => '0000000000000000000000000000000',
            'emailAddress' => '000000000000000000000000000000000000000000000000000',
            'remarks' => '000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000'
                       . '000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000'
                       . '000000000000000',
        ]));

        $this->assertEquals(12, $validation->count());
    }

    public function testValidateEmail()
    {
        $validation = $this->validator()->validate(Address::ofData([
            'emailAddress' => 'invalid',
            'country' => 'CH'
        ]));

        $this->assertEquals(1, $validation->count());
        $this->assertEquals('emailAddress', $validation->get(0)->getPropertyPath());
    }

    public function testValidateCountry()
    {
        $validation = $this->validator()->validate(Address::ofData([
            'country' => 'CH'
        ]));

        $this->assertEquals(0, $validation->count());
    }

    public function testValidateCountryInvalid()
    {
        $validation = $this->validator()->validate(Address::ofData([
            'country' => 'CHE'
        ]));

        $this->assertEquals(1, $validation->count());
        $this->assertEquals('country', $validation->get(0)->getPropertyPath());
        $this->assertEquals('This value is not a valid country.', $validation->get(0)->getMessage());
    }

    public function validator(): ValidatorInterface
    {
        return Validation::createValidatorBuilder()
            ->addYamlMapping(__DIR__ . '/../../../../src/Paloma/Shop/Resources/validation.yaml')
            ->getValidator();
    }
}