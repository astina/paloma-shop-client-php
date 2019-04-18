<?php

namespace Paloma\Shop\Customers;

use Exception;

class CustomersTestClient implements CustomersClientInterface
{
    private $customer = [
        'id' => '2',
        'contactAddress' => [

        ],
        'billingAddress' => [

        ],
        'shippingAddress' => [

        ],
    ];

    private $user = [
        'id' => '1',
        'username' => 'user',
    ];

    private $exception;

    public function __construct(Exception $exception = null)
    {
        $this->exception = $exception;
    }

    function register($customer)
    {
        if ($this->exception) {
            throw $this->exception;
        }

        return $this->customer;
    }

    function getCustomer($customerId)
    {
        if ($this->exception) {
            throw $this->exception;
        }

        return $this->customer;
    }

    function updateCustomer($customerId, $customer)
    {
        if ($this->exception) {
            throw $this->exception;
        }

        return $this->customer;
    }

    function updateAddress($customerId, $addressType, $address)
    {
        if ($this->exception) {
            throw $this->exception;
        }

        return $this->customer;
    }

    function confirmEmailAddress($token)
    {
        if ($this->exception) {
            throw $this->exception;
        }

        return $this->customer;
    }

    function exists($emailAddress)
    {
        if ($this->exception) {
            throw $this->exception;
        }

        return true;
    }

    function authenticateUser($username, $password)
    {
        if ($this->exception) {
            throw $this->exception;
        }

        return [
            'user' => $this->user,
            'customer' => $this->customer,
        ];
    }

    function updateUserPassword($password)
    {
        if ($this->exception) {
            throw $this->exception;
        }

        return [
            'user' => $this->user,
            'customer' => $this->customer,
        ];
    }

    function startUserPasswordReset($emailAddress, $confirmationBaseUrl)
    {
        if ($this->exception) {
            throw $this->exception;
        }
    }

    function getUserPasswordResetToken($token)
    {
        if ($this->exception) {
            throw $this->exception;
        }

        return [
            'user' => '1',
            'emailAddress' => 'test@astina.io',
            'token' => 'token',
            'confirmationUrl' => 'https://test',
            'expires' => null,
        ];
    }

    function finishUserPasswordReset($token, $password)
    {
        if ($this->exception) {
            throw $this->exception;
        }

        return [
            'user' => $this->user,
            'customer' => $this->customer,
        ];
    }

    function updateAdvertisingPreferences($customerId, $advertisingPrefs)
    {
        if ($this->exception) {
            throw $this->exception;
        }
        // TODO: Implement updateAdvertisingPreferences() method.
    }

    function createAdvertisingPrefs($advertisingPrefs)
    {
        // TODO: Implement createAdvertisingPrefs() method.
    }

    function confirmAdvertisingPrefs($token)
    {
        // TODO: Implement confirmAdvertisingPrefs() method.
    }

    function getLoyaltyPrograms($customerId)
    {
        // TODO: Implement getLoyaltyPrograms() method.
    }

    function updateLoyaltyPrograms($customerId, $program)
    {
        // TODO: Implement updateLoyaltyPrograms() method.
    }

    function getOrders($customerId, $pageNr = null, $pageSize = null, $sortOrder = null)
    {
        if ($this->exception) {
            throw $this->exception;
        }

        return [
            'content' => [],
        ];
    }

    function getOrder($customerId, $orderNr)
    {
        if ($this->exception) {
            throw $this->exception;
        }

        return [
            'id' => '123',
            'orderNumber' => 'order1',
            'currency' => 'CHF',
            'items' => [
                [
                    'sku' => '111',
                    'quantity' => 1,
                ]
            ]
        ];
    }

    function getOrderReceipt($customerId, $orderNr)
    {
        // TODO: Implement getOrderReceipt() method.
    }

    function getOrderStatus($orderNr)
    {
        // TODO: Implement getOrderStatus() method.
    }

    function getItemCodesPurchasedTogether($itemCode, $max = 5)
    {
        return [
            [ 'itemCode' => '111', 'count' => 15 ],
            [ 'itemCode' => '222', 'count' => 14 ],
            [ 'itemCode' => '333', 'count' => 13 ],
            [ 'itemCode' => '444', 'count' => 12 ],
            [ 'itemCode' => '555', 'count' => 11 ],
        ];
    }

    function addressCompleteHouse($country, $zipCode, $street, $house)
    {
        // TODO: Implement addressCompleteHouse() method.
    }

    function addressCompleteStreet($country, $zipCode, $street)
    {
        // TODO: Implement addressCompleteStreet() method.
    }

    function addressCompleteZip($country, $zipCity)
    {
        // TODO: Implement addressCompleteZip() method.
    }

    function addressCompleteStreetAndHouse($country, $zipCode, $streetAndHouse)
    {
        // TODO: Implement addressCompleteStreetAndHouse() method.
    }

    function addressValidate($address)
    {
        // TODO: Implement addressValidate() method.
    }
}