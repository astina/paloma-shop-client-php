<?php

namespace Paloma\Shop\Checkout;

use DateTime;
use GuzzleHttp\Exception\BadResponseException;
use GuzzleHttp\Exception\ServerException;
use Paloma\Shop\Error\BackendUnavailable;
use Paloma\Shop\Error\CartItemNotFound;
use Paloma\Shop\Error\InvalidCouponCode;
use Paloma\Shop\Error\InvalidInput;
use Paloma\Shop\Error\InvalidShippingTargetDate;
use Paloma\Shop\Error\NonElectronicPaymentMethod;
use Paloma\Shop\Error\OrderNotReadyForPayment;
use Paloma\Shop\Error\OrderNotReadyForPurchase;
use Paloma\Shop\Error\ProductVariantNotFound;
use Paloma\Shop\Error\ProductVariantUnavailable;
use Paloma\Shop\Error\UnknownPaymentMethod;
use Paloma\Shop\Error\UnknownShippingMethod;
use Paloma\Shop\PalomaClientInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class Checkout implements CheckoutInterface
{
    /**
     * @var PalomaClientInterface
     */
    private $client;

    /**
     * @var ValidatorInterface
     */
    private $validator;

    public function __construct(PalomaClientInterface $client, ValidatorInterface $validator)
    {
        $this->client = $client;
        $this->validator = $validator;
    }

    function getCart(): CartInterface
    {
        try {

            $checkoutOrder = $this->getCheckoutOrder();

            return $checkoutOrder->existsInSession()
                ? new Cart($checkoutOrder->get())
                : Cart::createEmpty();

        } catch (ServerException $se) {
            throw new BackendUnavailable();
        }
    }

    function addCartItem(string $sku, int $quantity = 1): CartInterface
    {
        try {

            $data = $this->getCheckoutOrder()->addItem($sku, $quantity);

            return new Cart($data);

        } catch (ServerException $se) {
            throw new BackendUnavailable();
        } catch (BadResponseException $bse) {
            if ($bse->getCode() === 404) {
                throw new ProductVariantNotFound();
            }
            throw new ProductVariantUnavailable();
        }
    }

    function updateCartItem(string $itemId, int $quantity): CartInterface
    {
        if ($quantity <= 0) {
            return $this->removeCartItem($itemId);
        }

        try {

            $data = $this->getCheckoutOrder()->updateQuantity($itemId, $quantity);

            return new Cart($data);

        } catch (ServerException $se) {
            throw new BackendUnavailable();
        } catch (BadResponseException $bse) {
            if ($bse->getCode() === 404) {
                throw new CartItemNotFound();
            }
            throw new ProductVariantUnavailable();
        }
    }

    function removeCartItem(string $itemId): CartInterface
    {
        try {

            $checkoutOrder = $this->getCheckoutOrder();
            if (!$checkoutOrder->existsInSession()) {
                return Cart::createEmpty();
            }

            $data = $checkoutOrder->removeItem($itemId);

            return new Cart($data);

        } catch (ServerException $se) {
            throw new BackendUnavailable();
        }
    }

    function setAddresses(AddressInterface $billingAddress, AddressInterface $shippingAddress = null): OrderDraftInterface
    {
        $validation = $this->validator->validate([
            'billingAddress' => $billingAddress,
            'shippingAddress' => $shippingAddress,
        ]);

        if ($validation->count() > 0) {
            throw new InvalidInput($validation);
        }

        try {

            $data = $this->getCheckoutOrder()->setAddresses(
                $this->toAddressArray($billingAddress),
                $this->toAddressArray($shippingAddress)
            );

            return new OrderDraft($data);

        } catch (ServerException $se) {
            throw new BackendUnavailable();
        }
    }

    /**
     * @return ShippingMethodInterface[]
     * @throws BackendUnavailable
     */
    function getShippingMethods(): array
    {
        try {

            $checkoutOrder = $this->getCheckoutOrder();
            if (!$checkoutOrder->existsInSession()) {
                return [];
            }

            $orderData = $checkoutOrder->get();

            $data = $this->getCheckout()->getShippingMethods($orderData['id']);

            return array_map(function($elem) use ($orderData) {
                return ShippingMethod::ofDataAndOrder($elem, $orderData);
            }, $data);

        } catch (ServerException $se) {
            throw new BackendUnavailable();
        }
    }

    function getShippingMethodOptions(string $shippingMethod, DateTime $from = null, DateTime $until = null): ShippingMethodOptionsInterface
    {
        try {

            $checkoutOrder = $this->getCheckoutOrder();
            if (!$checkoutOrder->existsInSession()) {
                return new ShippingMethodOptions([]);
            }

            $data = $this->getCheckout()->getShippingMethodOptions(
                $checkoutOrder->get()['id'],
                $from ? $from->format('Y-m-d') : null,
                $until ? $until->format('Y-m-d') : null
            );

            return new ShippingMethodOptions($data);

        } catch (ServerException $se) {
            throw new BackendUnavailable();
        } catch (BadResponseException $be) {
            if ($be->getCode() === 404) {
                throw new UnknownShippingMethod();
            }
            throw new InvalidInput(); // TODO more info
        }
    }

    function setShippingMethod(string $shippingMethod, DateTime $targetDate = null): OrderDraftInterface
    {
        try {

            $orderData = $this->getCheckoutOrder()->get();

            $data = $this->getCheckout()->setShippingMethod($orderData['id'], [
                'name' => $shippingMethod,
                'targetDate' => $targetDate
                    ? $targetDate->format('Y-m-d')
                    : null,
            ]);

            return new OrderDraft($data);

        } catch (ServerException $se) {
            throw new BackendUnavailable();
        } catch (BadResponseException $be) {
            if ($be->getCode() === 404) {
                throw new UnknownShippingMethod();
            }
            throw new InvalidShippingTargetDate();
        }
    }

    /**
     * @return PaymentMethodInterface[]
     * @throws BackendUnavailable
     */
    function getPaymentMethods(): array
    {
        try {

            $data = $this->getCheckoutOrder()->getPaymentMethods();

            return array_map(function($elem) {
                return new PaymentMethod($elem);
            }, $data);

        } catch (ServerException $se) {
            throw new BackendUnavailable();
        }
    }

    function setPaymentMethod(string $paymentMethod): OrderDraftInterface
    {
        try {

            $data = $this->getCheckoutOrder()->setPaymentMethod($paymentMethod);

            return new OrderDraft($data);

        } catch (ServerException $se) {
            throw new BackendUnavailable();
        } catch (BadResponseException $be) {
            throw new UnknownPaymentMethod();
        }
    }

    function addCouponCode(string $couponCode): OrderDraftInterface
    {
        try {

            $orderData = $this->getCheckoutOrder()->get();

            $data = $this->getCheckout()->addCoupon($orderData['id'], $couponCode);

            return new OrderDraft($data);

        } catch (ServerException $se) {
            throw new BackendUnavailable();
        } catch (BadResponseException $be) {
            throw new InvalidCouponCode($be);
        }
    }

    function removeCouponCode(String $couponCode): OrderDraftInterface
    {
        try {

            $orderData = $this->getCheckoutOrder()->get();

            $data = $this->getCheckout()->deleteCoupon($orderData['id'], $couponCode);

            return new OrderDraft($data);

        }  catch (ServerException $se) {
            throw new BackendUnavailable();
        }
    }

    function initializePayment(PaymentInitParametersInterface $params): PaymentDraftInterface
    {
        try {
            $checkoutOrder = $this->getCheckoutOrder();
            if (!$checkoutOrder->existsInSession()) {
                throw new OrderNotReadyForPayment();
            }
        } catch (ServerException $se) {
            throw new BackendUnavailable();
        }

        $orderData = $checkoutOrder->get();
        if (!isset($orderData['paymentMethod']['type']) || $orderData['paymentMethod']['type'] !== 'electronic') {
            throw new NonElectronicPaymentMethod();
        }

        if ($orderData['status'] !== 'finalized') {
            try {
                $this->getCheckout()->finalizeOrder($orderData['id']);
            } catch (BadResponseException $bre) {
                throw new OrderNotReadyForPayment(); // TODO add error details
            }
        }

        try {

            $data = $checkoutOrder->initPayment([
                'successUrl' => $params->getSuccessUrl(),
                'cancelUrl' => $params->getCancelUrl(),
                'errorUrl' => $params->getErrorUrl(),
            ]);

            return new PaymentDraft($data);

        } catch (ServerException $se) {
            throw new BackendUnavailable();
        }
    }

    function purchase(): OrderPurchaseInterface
    {
        try {
            $checkoutOrder = $this->getCheckoutOrder();
            if (!$checkoutOrder->existsInSession()) {
                throw new OrderNotReadyForPurchase();
            }
        } catch (ServerException $se) {
            throw new BackendUnavailable();
        }

        $orderData = $checkoutOrder->get();

        if ($orderData['status'] !== 'finalized') {
            try {
                $this->getCheckout()->finalizeOrder($orderData['id']);
            } catch (BadResponseException $bre) {
                throw new OrderNotReadyForPurchase(); // TODO add error details
            }
        }

        try {

            $data = $checkoutOrder->purchase();

            return new OrderPurchase($data);

        } catch (ServerException $se) {
            throw new BackendUnavailable();
        }
    }

    private function toAddressArray(AddressInterface $address)
    {
        if ($address === null) {
            return null;
        }

        return [
            'title' => $address->getTitle(),
            'firstName' => $address->getFirstName(),
            'lastName' => $address->getLastName(),
            'company' => $address->getCompany(),
            'street' => $address->getStreet(),
            'zipCode' => $address->getZipCode(),
            'country' => $address->getCountry(),
            'phoneNumber' => $address->getPhoneNumber(),
            'emailAddress' => $address->getEmailAddress(),
            'remarks' => $address->getRemarks(),
        ];
    }

    /**
     * @return CheckoutOrder
     */
    public function getCheckoutOrder(): CheckoutOrder
    {
        return $this->getCheckout()->checkoutOrder();
    }

    /**
     * @return CheckoutClientInterface
     */
    public function getCheckout(): CheckoutClientInterface
    {
        return $this->client->checkout();
    }
}