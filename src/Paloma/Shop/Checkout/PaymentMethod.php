<?php

namespace Paloma\Shop\Checkout;

class PaymentMethod implements PaymentMethodInterface
{
    private $data;

    private $selected;

    private $paymentInstruments;

    public static function ofDataAndOrder(array $data, array $order): PaymentMethod
    {
        return new PaymentMethod(
            $data,
            isset($order['paymentMethod']['name']) && $order['paymentMethod']['name'] === $data['name'],
            isset($order['paymentMethod']['paymentInstrumentId']) ? $order['paymentMethod']['paymentInstrumentId'] : null
        );
    }

    public function __construct(array $data, bool $selected = false, ?string $selectedPaymentInstrumentId = null)
    {
        $this->data = $data;
        $this->selected = $selected;
        $this->paymentInstruments = array_map(function($elem) use ($selectedPaymentInstrumentId) {
            return new PaymentInstrument($elem, $elem['id'] === $selectedPaymentInstrumentId);
        }, $data['paymentInstruments'] ?? []);
    }

    function getName(): string
    {
        return $this->data['name'];
    }

    function getType(): string
    {
        return $this->data['type'];
    }

    function getProvider(): ?string
    {
        return isset($this->data['provider'])
            ? $this->data['provider']
            : null;
    }

    function isSelected(): bool
    {
        return $this->selected;
    }

    function getPaymentInstruments(): array
    {
        return $this->paymentInstruments;
    }
}