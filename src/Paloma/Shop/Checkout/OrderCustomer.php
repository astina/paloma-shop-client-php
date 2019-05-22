<?php

namespace Paloma\Shop\Checkout;

use Paloma\Shop\Customers\CustomerBasics;

class OrderCustomer extends CustomerBasics implements OrderCustomerInterface
{
    public function __construct(array $data, string $locale)
    {
        $data['locale'] = $locale;
        parent::__construct($data);
    }

    function getUserId(): ?string
    {
        return $this->data['userId'];
    }
}