<?php

namespace Paloma\Shop\Customers;

use Exception;

class CustomersTestClient implements CustomersClientInterface
{
    private $customer = [
        'id' => '2',
        'contactAddress' => [
            'title' => 'mr',
        ],
        'billingAddress' => [
            'title' => 'mr',
        ],
        'shippingAddress' => [
            'title' => 'mr',
        ],
        'users' => [
            [

                'id' => '1',
                'username' => 'user',
            ]
        ]
    ];

    private $user = [
        'id' => '1',
        'username' => 'user',
    ];

    private $exception;

    private $exceptionForMethods;

    public function __construct(Exception $exception = null, array $exceptionForMethods = [])
    {
        $this->exception = $exception;
        $this->exceptionForMethods = $exceptionForMethods;
    }

    private function throwException($method)
    {
        if ($this->exception) {
            throw $this->exception;
        }

        if (isset($this->exceptionForMethods[$method])) {
            throw $this->exceptionForMethods[$method];
        }
    }

    function register($customer)
    {
        $this->throwException(__FUNCTION__);
        return $this->customer;
    }

    function getCustomer($customerId)
    {
        $this->throwException(__FUNCTION__);
        return $this->customer;
    }

    function updateCustomer($customerId, $customer)
    {
        $this->throwException(__FUNCTION__);
        return $this->customer;
    }

    function updateAddress($customerId, $addressType, $address)
    {
        $this->throwException(__FUNCTION__);
        return $this->customer;
    }

    function confirmEmailAddress($token)
    {
        $this->throwException(__FUNCTION__);
        return $this->customer;
    }

    function exists($emailAddress)
    {
        $this->throwException(__FUNCTION__);
        return true;
    }

    function authenticateUser($username, $password)
    {
        $this->throwException(__FUNCTION__);
        return [
            'user' => $this->user,
            'customer' => $this->customer,
        ];
    }

    function updateUserPassword($password)
    {
        $this->throwException(__FUNCTION__);
        return [
            'user' => $this->user,
            'customer' => $this->customer,
        ];
    }

    function startUserPasswordReset($emailAddress, $confirmationBaseUrl)
    {
        $this->throwException(__FUNCTION__);
    }

    function getUserPasswordResetToken($token)
    {
        $this->throwException(__FUNCTION__);
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
        $this->throwException(__FUNCTION__);
        return [
            'user' => $this->user,
            'customer' => $this->customer,
        ];
    }

    function updateAdvertisingPreferences($customerId, $advertisingPrefs)
    {
        $this->throwException(__FUNCTION__);
    }

    function createAdvertisingPrefs($advertisingPrefs)
    {
        $this->throwException(__FUNCTION__);
    }

    function confirmAdvertisingPrefs($token)
    {
        $this->throwException(__FUNCTION__);
    }

    function getLoyaltyPrograms($customerId)
    {
        $this->throwException(__FUNCTION__);
    }

    function updateLoyaltyPrograms($customerId, $program)
    {
        $this->throwException(__FUNCTION__);
    }

    function getOrders($customerId, $pageNr = null, $pageSize = null, $sortOrder = null)
    {
        $this->throwException(__FUNCTION__);
        return [
            'content' => [],
        ];
    }

    function getOrder($customerId, $orderNr)
    {
        $this->throwException(__FUNCTION__);
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
        $this->throwException(__FUNCTION__);
    }

    function getOrderStatus($orderNr)
    {
        $this->throwException(__FUNCTION__);
    }

    function getItemCodesPurchasedTogether($itemCode, $max = 5)
    {
        $this->throwException(__FUNCTION__);
        return [
            ['itemCode' => '111', 'count' => 15],
            ['itemCode' => '222', 'count' => 14],
            ['itemCode' => '333', 'count' => 13],
            ['itemCode' => '444', 'count' => 12],
            ['itemCode' => '555', 'count' => 11],
        ];
    }

    function addressCompleteHouse($country, $zipCode, $street, $house)
    {
        $this->throwException(__FUNCTION__);
    }

    function addressCompleteStreet($country, $zipCode, $street)
    {
        $this->throwException(__FUNCTION__);
    }

    function addressCompleteZip($country, $zipCity)
    {
        $this->throwException(__FUNCTION__);
    }

    function addressCompleteStreetAndHouse($country, $zipCode, $streetAndHouse)
    {
        $this->throwException(__FUNCTION__);
    }

    function addressValidate($address)
    {
        $this->throwException(__FUNCTION__);
    }

    function getProducts($customerId, $pageNr = null, $pageSize = null)
    {
        $this->throwException(__FUNCTION__);
    }

    function getPaymentInstruments($customerId)
    {
        $this->throwException(__FUNCTION__);

        return [
            [
                'id' => 'PI123',
                'means' => 'visa',
                'type' => 'Visa',
                'maskedCardNumber' => '1234xxxxxxxx1234',
                'expirationYear' => ((int) date('y')) + 2,
                'expirationMonth' => 12,
                'expired' => false,
            ]
        ];
    }

    function deletePaymentInstrument($customerId, $paymentInstrumentId)
    {
        $this->throwException(__FUNCTION__);
    }
}