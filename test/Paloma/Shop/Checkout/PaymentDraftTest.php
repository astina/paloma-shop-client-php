<?php

namespace Paloma\Shop\Checkout;

use PHPUnit\Framework\TestCase;

class PaymentDraftTest extends TestCase
{
    public function testPaymentDraft()
    {
        $draft = new PaymentDraft([
            'reference' => '123',
            'amount' => '12.30',
            'providerRequest' => [
                [ 'name' => 'param1', 'value' => 'value1' ],
                [ 'name' => 'param2', 'value' => 'value2' ],
            ]
        ]);

        $this->assertEquals('123', $draft->getReference());
        $this->assertEquals('12.30', $draft->getAmount());
        $this->assertEquals('value1', $draft->getProviderParams()['param1']);
        $this->assertEquals('value2', $draft->getProviderParams()['param2']);
    }
}