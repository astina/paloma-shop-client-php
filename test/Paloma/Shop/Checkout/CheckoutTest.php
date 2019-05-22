<?php

namespace Paloma\Shop\Checkout;

use DateTime;
use Exception;
use GuzzleHttp\Exception\BadResponseException;
use GuzzleHttp\Exception\ServerException;
use GuzzleHttp\Exception\TransferException;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use Paloma\Shop\Common\Address;
use Paloma\Shop\Error\BackendUnavailable;
use Paloma\Shop\Error\CartItemNotFound;
use Paloma\Shop\Error\InvalidCouponCode;
use Paloma\Shop\Error\InvalidInput;
use Paloma\Shop\Error\InvalidShippingTargetDate;
use Paloma\Shop\Error\NonElectronicPaymentMethod;
use Paloma\Shop\Error\OrderNotReadyForPayment;
use Paloma\Shop\Error\ProductVariantNotFound;
use Paloma\Shop\Error\ProductVariantUnavailable;
use Paloma\Shop\Error\UnknownPaymentMethod;
use Paloma\Shop\Error\UnknownShippingMethod;
use Paloma\Shop\PalomaTestClient;
use Paloma\Shop\Security\TestUserProvider;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Validator\RecursiveValidator;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class CheckoutTest extends TestCase
{
    public function testGetCartEmpty()
    {
        $checkout = $this->checkout(false);

        /** @noinspection PhpUnhandledExceptionInspection */
        $cart = $checkout->getCart();

        $this->assertInstanceOf(CartInterface::class, $cart);
        $this->assertTrue($cart->isEmpty());
        $this->assertEquals(0, count($cart->getItems()));
    }

    public function testGetCartWithItems()
    {
        /** @noinspection PhpUnhandledExceptionInspection */
        $cart = $this->checkout()->getCart();

        $this->assertInstanceOf(CartInterface::class, $cart);
        $this->assertFalse($cart->isEmpty());
        $this->assertEquals(2, count($cart->getItems()));
        $this->assertEquals('CHF 199.00', $cart->getItems()[0]->getUnitPrice());
        $this->assertEquals('CHF 199.00', $cart->getItems()[0]->getItemPrice());
    }

    public function testGetCartWith503Response()
    {
        $this->expectException(BackendUnavailable::class);

        /** @noinspection PhpUnhandledExceptionInspection */
        $this->checkout(true, $this->createServerException())->getCart();
    }

    public function testAddCartItem()
    {
        /** @noinspection PhpUnhandledExceptionInspection */
        $cart = $this->checkout()->addCartItem('123');

        $this->assertInstanceOf(CartInterface::class, $cart);
    }

    public function testAddCartItemWith503Response()
    {
        $this->expectException(BackendUnavailable::class);

        /** @noinspection PhpUnhandledExceptionInspection */
        $this->checkout(true, $this->createServerException())->addCartItem('123');
    }

    public function testAddCartItemWith404Response()
    {
        $this->expectException(ProductVariantNotFound::class);

        /** @noinspection PhpUnhandledExceptionInspection */
        $this->checkout(true, $this->createNotFoundException())->addCartItem('123');
    }

    public function testAddCartItemWith400Response()
    {
        $this->expectException(ProductVariantUnavailable::class);

        /** @noinspection PhpUnhandledExceptionInspection */
        $this->checkout(true, $this->createBadRequestException())->addCartItem('123');
    }

    public function testUpdateCartItem()
    {
        /** @noinspection PhpUnhandledExceptionInspection */
        $cart = $this->checkout()->updateCartItem('123', 1);

        $this->assertInstanceOf(CartInterface::class, $cart);
    }

    public function testUpdateCartItemWithZero()
    {
        /** @noinspection PhpUnhandledExceptionInspection */
        $cart = $this->checkout()->updateCartItem('123', 0);

        $this->assertInstanceOf(CartInterface::class, $cart);
    }

    public function testUpdateCartItemWith503Response()
    {
        $this->expectException(BackendUnavailable::class);

        /** @noinspection PhpUnhandledExceptionInspection */
        $this->checkout(true, $this->createServerException())->updateCartItem('123', 1);
    }

    public function testUpdateCartItemWith404Response()
    {
        $this->expectException(CartItemNotFound::class);

        /** @noinspection PhpUnhandledExceptionInspection */
        $this->checkout(true, $this->createNotFoundException())->updateCartItem('123', 1);
    }

    public function testUpdateCartItemWith400Response()
    {
        $this->expectException(ProductVariantUnavailable::class);

        /** @noinspection PhpUnhandledExceptionInspection */
        $this->checkout(true, $this->createBadRequestException())->updateCartItem('123', 1);
    }

    public function testRemoveCartItem()
    {
        /** @noinspection PhpUnhandledExceptionInspection */
        $cart = $this->checkout()->removeCartItem('123');

        $this->assertInstanceOf(CartInterface::class, $cart);
    }

    public function testRemoveCartItemWithEmptyCart()
    {
        $checkout = $this->checkout(false);

        /** @noinspection PhpUnhandledExceptionInspection */
        $cart = $checkout->removeCartItem('123');

        $this->assertInstanceOf(CartInterface::class, $cart);
    }

    public function testRemoveCartItemWith503Response()
    {
        $this->expectException(BackendUnavailable::class);

        /** @noinspection PhpUnhandledExceptionInspection */
        $this->checkout(true, $this->createServerException())->removeCartItem('123');
    }

    public function testSetAddresses()
    {
        $address = Address::ofData([
            'country' => 'CH',
            'emailAddress' => 'test@astina.io',
        ]);

        /** @noinspection PhpUnhandledExceptionInspection */
        $cart = $this->checkout()->setAddresses($address, $address);

        $this->assertInstanceOf(OrderDraftInterface::class, $cart);
    }

    public function testSetAddressesWithInvalidInput()
    {
        $this->expectException(InvalidInput::class);

        $address1 = Address::ofData([
            'country' => 'Switzerland', // should be country code
        ]);

        $address2 = Address::ofData([
            'emailAddress' => 'invalid',
        ]);

        /** @noinspection PhpUnhandledExceptionInspection */
        $this->checkout()->setAddresses($address1, $address2);
    }

    public function testSetAddressesWith503Response()
    {
        $this->expectException(BackendUnavailable::class);

        $address = Address::ofData([
            'country' => 'CH'
        ]);

        /** @noinspection PhpUnhandledExceptionInspection */
        $this->checkout(true, $this->createServerException())->setAddresses($address, null);
    }

    public function testGetShippingMethods()
    {
        /** @noinspection PhpUnhandledExceptionInspection */
        $shippingMethods = $this->checkout()->getShippingMethods();

        $this->assertNotNull($shippingMethods);
    }

    public function testGetShippingMethodsWithEmptyCart()
    {
        $checkout = $this->checkout(false);

        /** @noinspection PhpUnhandledExceptionInspection */
        $shippingMethods = $checkout->getShippingMethods();

        $this->assertNotNull($shippingMethods);
        $this->assertEquals([], $shippingMethods);
    }

    public function testGetShippingMethodsWith503Response()
    {
        $this->expectException(BackendUnavailable::class);

        /** @noinspection PhpUnhandledExceptionInspection */
        $this->checkout(true, $this->createServerException())->getShippingMethods();
    }

    public function testGetShippingMethodOptions()
    {
        /** @noinspection PhpUnhandledExceptionInspection */
        $options = $this->checkout()->getShippingMethodOptions('method1');

        $this->assertNotNull($options);
    }

    public function testGetShippingMethodOptionsWithDateRange()
    {
        /** @noinspection PhpUnhandledExceptionInspection */
        $options = $this->checkout()->getShippingMethodOptions('method1', new DateTime(), new DateTime());

        $this->assertNotNull($options);
    }

    public function testGetShippingMethodOptionsWithEmptyCart()
    {
        $checkout = $this->checkout(false);

        /** @noinspection PhpUnhandledExceptionInspection */
        $options = $checkout->getShippingMethodOptions('method1');

        $this->assertNotNull($options);
        $this->assertEquals(0, count($options->getDelivery()));
    }

    public function testGetShippingMethodOptionsWith503Response()
    {
        $this->expectException(BackendUnavailable::class);

        /** @noinspection PhpUnhandledExceptionInspection */
        $this->checkout(true, $this->createServerException())->getShippingMethodOptions('method1');
    }

    public function testGetShippingMethodOptionsWith404Response()
    {
        $this->expectException(UnknownShippingMethod::class);

        /** @noinspection PhpUnhandledExceptionInspection */
        $this->checkout(true, $this->createNotFoundException())->getShippingMethodOptions('method1');
    }

    public function testGetShippingMethodOptionsWith400Response()
    {
        $this->expectException(InvalidInput::class);

        /** @noinspection PhpUnhandledExceptionInspection */
        $this->checkout(true, $this->createBadRequestException())->getShippingMethodOptions('method1');
    }

    public function testSetShippingMethod()
    {
        /** @noinspection PhpUnhandledExceptionInspection */
        $cart = $this->checkout()->setShippingMethod('method1');

        $this->assertInstanceOf(OrderDraftInterface::class, $cart);
    }

    public function testSetShippingMethodWithTargetDate()
    {
        /** @noinspection PhpUnhandledExceptionInspection */
        $cart = $this->checkout()->setShippingMethod('method1', DateTime::createFromFormat('Y-m-d', '2019-04-01'));

        $this->assertInstanceOf(OrderDraftInterface::class, $cart);
    }

    public function testSetShippingMethodWith503Response()
    {
        $this->expectException(BackendUnavailable::class);

        /** @noinspection PhpUnhandledExceptionInspection */
        $this->checkout(true, $this->createServerException())->setShippingMethod('method1');
    }

    public function testSetShippingMethodWith404Response()
    {
        $this->expectException(UnknownShippingMethod::class);

        /** @noinspection PhpUnhandledExceptionInspection */
        $this->checkout(true, $this->createNotFoundException())->setShippingMethod('method1');
    }

    public function testGetPaymentMethods()
    {
        /** @noinspection PhpUnhandledExceptionInspection */
        $paymentMethods = $this->checkout()->getPaymentMethods();

        $this->assertNotNull($paymentMethods);
    }

    public function testGetPaymentMethodsWith503Response()
    {
        $this->expectException(BackendUnavailable::class);

        /** @noinspection PhpUnhandledExceptionInspection */
        $this->checkout(true, $this->createServerException())->getPaymentMethods();
    }

    public function testSetPaymentMethod()
    {
        /** @noinspection PhpUnhandledExceptionInspection */
        $cart = $this->checkout()->setPaymentMethod('method1');

        $this->assertInstanceOf(OrderDraftInterface::class, $cart);
    }

    public function testSetPaymentMethodWith503Response()
    {
        $this->expectException(BackendUnavailable::class);

        /** @noinspection PhpUnhandledExceptionInspection */
        $this->checkout(true, $this->createServerException())->setPaymentMethod('method1');
    }

    public function testSetPaymentMethodWith404Response()
    {
        $this->expectException(UnknownPaymentMethod::class);

        /** @noinspection PhpUnhandledExceptionInspection */
        $this->checkout(true, $this->createBadRequestException())->setPaymentMethod('method1');
    }

    public function testAddCouponCode()
    {
        /** @noinspection PhpUnhandledExceptionInspection */
        $cart = $this->checkout()->addCouponCode('123');

        $this->assertInstanceOf(OrderDraftInterface::class, $cart);
    }

    public function testAddCouponCodeWith503Response()
    {
        $this->expectException(BackendUnavailable::class);

        /** @noinspection PhpUnhandledExceptionInspection */
        $this->checkout(true, $this->createServerException())->addCouponCode('123');
    }

    public function testAddCouponCodeWith400Response()
    {
        $this->expectException(InvalidCouponCode::class);

        /** @noinspection PhpUnhandledExceptionInspection */
        $this->checkout(true, $this->createBadRequestException())->addCouponCode('123');
    }

    public function testRemoveCouponCode()
    {
        /** @noinspection PhpUnhandledExceptionInspection */
        $cart = $this->checkout()->removeCouponCode('123');

        $this->assertInstanceOf(OrderDraftInterface::class, $cart);
    }

    public function testRemoveCouponCodeWith503Response()
    {
        $this->expectException(BackendUnavailable::class);

        /** @noinspection PhpUnhandledExceptionInspection */
        $this->checkout(true, $this->createServerException())->removeCouponCode('123');
    }

    public function testInitializePayment()
    {
        /** @noinspection PhpUnhandledExceptionInspection */
        $payment = $this->checkout()->initializePayment(new PaymentInitParameters('url1', 'url2', 'url3'));

        $this->assertInstanceOf(PaymentDraftInterface::class, $payment);
    }

    public function testInitializePaymentWithEmptyCart()
    {
        $this->expectException(OrderNotReadyForPayment::class);

        /** @noinspection PhpUnhandledExceptionInspection */
        $this->checkout(false)->initializePayment(new PaymentInitParameters('url1', 'url2', 'url3'));
    }

    public function testInitializePaymentWithNonElectronicPaymentMethod()
    {
        $this->expectException(NonElectronicPaymentMethod::class);

        $order = json_decode(file_get_contents(__DIR__ . '/checkout_order.json'), true);
        $order['paymentMethod']['type'] = 'invoice';

        $checkout = new Checkout((new PalomaTestClient())->withCheckout(new CheckoutTestClient($order)), new TestUserProvider(), $this->validator());

        /** @noinspection PhpUnhandledExceptionInspection */
        $checkout->initializePayment(new PaymentInitParameters('url1', 'url2', 'url3'));
    }

    public function testInitializePaymentWith503Response()
    {
        $this->expectException(BackendUnavailable::class);

        /** @noinspection PhpUnhandledExceptionInspection */
        $this->checkout(true, $this->createServerException())->initializePayment(new PaymentInitParameters('url1', 'url2', 'url3'));
    }

    public function testPurchase()
    {
        /** @noinspection PhpUnhandledExceptionInspection */
        $orderPurchase = $this->checkout()->purchase();

        $this->assertInstanceOf(OrderPurchaseInterface::class, $orderPurchase);
    }

    public function testPurchaseWith503Response()
    {
        $this->expectException(BackendUnavailable::class);

        /** @noinspection PhpUnhandledExceptionInspection */
        $this->checkout(true, $this->createServerException())->purchase();
    }

    private function checkout(bool $withCart = true, Exception $exception = null): Checkout
    {
        $order = null;
        if ($withCart) {
            $order = json_decode(file_get_contents(__DIR__ . '/checkout_order.json'), true);
        }

        $validator = $this->validator();

        return new Checkout((new PalomaTestClient())->withCheckout(new CheckoutTestClient($order, $exception)), new TestUserProvider(), $validator);
    }

    private function validator(): ValidatorInterface
    {
        return Validation::createValidatorBuilder()
            ->addYamlMapping(__DIR__ . '/../../../../src/Paloma/Shop/Resources/validation.yaml')
            ->getValidator();
    }

    /**
     * @return TransferException
     */
    private function createServerException(): TransferException
    {
        return new TransferException(
            'test'
        );
    }

    /**
     * @return BadResponseException
     */
    private function createNotFoundException(): BadResponseException
    {
        return new BadResponseException(
            'test',
            new Request('GET', 'https://example.org'),
            new Response(404)
        );
    }

    /**
     * @return BadResponseException
     */
    private function createBadRequestException(): BadResponseException
    {
        return new BadResponseException(
            'test',
            new Request('GET', 'https://example.org'),
            new Response(400)
        );
    }
}