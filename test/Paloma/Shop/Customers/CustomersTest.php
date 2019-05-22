<?php

namespace Paloma\Shop\Customers;

use DateTime;
use Exception;
use GuzzleHttp\Exception\BadResponseException;
use GuzzleHttp\Exception\ServerException;
use GuzzleHttp\Exception\TransferException;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use Paloma\Shop\Common\AddressInterface;
use Paloma\Shop\Error\BackendUnavailable;
use Paloma\Shop\Error\BadCredentials;
use Paloma\Shop\Error\InvalidConfirmationToken;
use Paloma\Shop\Error\InvalidInput;
use Paloma\Shop\Error\OrderNotFound;
use Paloma\Shop\PalomaTestClient;
use Paloma\Shop\PalomaTestConfig;
use Paloma\Shop\Security\TestUserProvider;
use Paloma\Shop\Security\UserDetailsInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class CustomersTest extends TestCase
{
    public function testRegisterCustomer()
    {
        /** @noinspection PhpUnhandledExceptionInspection */
        $customer = $this->customers()->registerCustomer(new CustomerDraft(
            'test@astina.io',
            '123456',
            'de_CH',
            'Hans',
            'Muster',
            null,
            'male',
            DateTime::createFromFormat('Y-m-d', '1980-01-01')
        ));

        $this->assertInstanceOf(CustomerInterface::class, $customer);
    }

    public function testRegisterCustomerInvalid()
    {
        $this->expectException(InvalidInput::class);

        /** @noinspection PhpUnhandledExceptionInspection */
        $this->customers()->registerCustomer(new CustomerDraft(
            'invalid',
            '',
            'invalid',
            '',
            '',
            null,
            'invalid',
            DateTime::createFromFormat('Y-m-d', '1980-01-01')));
    }

    public function testRegisterCustomerWith503Response()
    {
        $this->expectException(BackendUnavailable::class);

        /** @noinspection PhpUnhandledExceptionInspection */
        $this->customers($this->createServerException())->registerCustomer(new CustomerDraft(
            'test@astina.io',
            '123456',
            'de_CH',
            'Hans',
            'Muster',
            null,
            'male',
            DateTime::createFromFormat('Y-m-d', '1980-01-01')
        ));
    }

    public function testRegisterCustomerWith400Response()
    {
        $this->expectException(InvalidInput::class);

        /** @noinspection PhpUnhandledExceptionInspection */
        $this->customers($this->createBadRequestException())->registerCustomer(new CustomerDraft(
            'test@astina.io',
            '123456',
            'de_CH',
            'Hans',
            'Muster',
            null,
            'male',
            DateTime::createFromFormat('Y-m-d', '1980-01-01')
        ));
    }

    public function testGetCustomer()
    {
        /** @noinspection PhpUnhandledExceptionInspection */
        $customer = $this->customers()->getCustomer();

        $this->assertInstanceOf(CustomerInterface::class, $customer);
    }

    public function testGetCustomerWith503Response()
    {
        $this->expectException(BackendUnavailable::class);

        /** @noinspection PhpUnhandledExceptionInspection */
        $this->customers($this->createServerException())->getCustomer();
    }

    public function testUpdateCustomer()
    {
        /** @noinspection PhpUnhandledExceptionInspection */
        $customer = $this->customers()->updateCustomer(new CustomerUpdate(
            'test@astina.io',
            'fr_CH',
            'Hans',
            'Muster',
            null,
            'unknown',
            null
        ));

        $this->assertInstanceOf(CustomerInterface::class, $customer);
    }

    public function testUpdateCustomerInvalid()
    {
        $this->expectException(InvalidInput::class);

        /** @noinspection PhpUnhandledExceptionInspection */
        $this->customers()->updateCustomer(new CustomerUpdate(
            'test@astina.io',
            'invalid',
            null,
            null,
            null,
            'invalid',
            null
        ));
    }

    public function testUpdateCustomerWith503Response()
    {
        $this->expectException(BackendUnavailable::class);

        /** @noinspection PhpUnhandledExceptionInspection */
        $this->customers($this->createServerException())->updateCustomer(new CustomerUpdate(
            'test@astina.io',
            'fr_CH',
            'Hans',
            'Muster',
            null,
            'unknown',
            null
        ));
    }

    public function testUpdateCustomerWith400Response()
    {
        $this->expectException(InvalidInput::class);

        /** @noinspection PhpUnhandledExceptionInspection */
        $this->customers($this->createBadRequestException())->updateCustomer(new CustomerUpdate(
            'test@astina.io',
            'fr_CH',
            'Hans',
            'Muster',
            null,
            'unknown',
            null
        ));
    }

    public function testUpdateAddressBilling()
    {
        /** @noinspection PhpUnhandledExceptionInspection */
        $address = $this->customers()->updateAddress(new AddressUpdate(
            'billing',
            'mr',
            'Hans',
            'Muster',
            null,
            null,
            null,
            null,
            'CH',
            null,
            null,
            null
        ));

        $this->assertInstanceOf(AddressInterface::class, $address);
    }

    public function testUpdateAddressShipping()
    {
        /** @noinspection PhpUnhandledExceptionInspection */
        $address = $this->customers()->updateAddress(new AddressUpdate(
            'shipping',
            'mr',
            'Hans',
            'Muster',
            null,
            null,
            null,
            null,
            'CH',
            null,
            null,
            null
        ));

        $this->assertInstanceOf(AddressInterface::class, $address);
    }

    public function testUpdateAddressContact()
    {
        /** @noinspection PhpUnhandledExceptionInspection */
        $address = $this->customers()->updateAddress(new AddressUpdate(
            'contact',
            'mr',
            'Hans',
            'Muster',
            null,
            null,
            null,
            null,
            'CH',
            null,
            null,
            null
        ));

        $this->assertInstanceOf(AddressInterface::class, $address);
    }

    public function testUpdateAddressInvalid()
    {
        $this->expectException(InvalidInput::class);

        /** @noinspection PhpUnhandledExceptionInspection */
        $this->customers()->updateAddress(new AddressUpdate(
            'invalid',
            'mr',
            'Hans',
            'Muster',
            null,
            null,
            null,
            null,
            'invalid',
            null,
            'invalid',
            null
        ));
    }

    public function testUpdateAddressWith503Response()
    {
        $this->expectException(BackendUnavailable::class);

        /** @noinspection PhpUnhandledExceptionInspection */
        $this->customers($this->createServerException())->updateAddress(new AddressUpdate(
            'billing',
            'mr',
            'Hans',
            'Muster',
            null,
            null,
            null,
            null,
            'CH',
            null,
            null,
            null
        ));
    }

    public function testUpdateAddressWith400Response()
    {
        $this->expectException(InvalidInput::class);

        /** @noinspection PhpUnhandledExceptionInspection */
        $this->customers($this->createBadRequestException())->updateAddress(new AddressUpdate(
            'billing',
            'mr',
            'Hans',
            'Muster',
            null,
            null,
            null,
            null,
            'CH',
            null,
            null,
            null
        ));
    }

    public function testConfirmEmailAddress()
    {
        /** @noinspection PhpUnhandledExceptionInspection */
        $user = $this->customers()->confirmEmailAddress('token');

        $this->assertInstanceOf(UserDetailsInterface::class, $user);
    }

    public function testConfirmEmailAddressWith503Response()
    {
        $this->expectException(BackendUnavailable::class);

        /** @noinspection PhpUnhandledExceptionInspection */
        $this->customers($this->createServerException())->confirmEmailAddress('token');
    }

    public function testConfirmEmailAddressWith400Response()
    {
        $this->expectException(InvalidConfirmationToken::class);

        /** @noinspection PhpUnhandledExceptionInspection */
        $this->customers($this->createBadRequestException())->confirmEmailAddress('token');
    }

    public function testExistsCustomerByEmailAddress()
    {
        /** @noinspection PhpUnhandledExceptionInspection */
        $exists = $this->customers()->existsCustomerByEmailAddress('test@astina.io');

        $this->assertTrue($exists);
    }

    public function testExistsCustomerByEmailAddressWith503Response()
    {
        $this->expectException(BackendUnavailable::class);

        /** @noinspection PhpUnhandledExceptionInspection */
        $this->customers($this->createServerException())->existsCustomerByEmailAddress('test@astina.io');
    }

    public function testExistsCustomerByEmailAddressWith404Response()
    {
        /** @noinspection PhpUnhandledExceptionInspection */
        $exists = $this->customers($this->createNotFoundException())->existsCustomerByEmailAddress('test@astina.io');

        $this->assertFalse($exists);
    }

    public function testAuthenticate()
    {
        /** @noinspection PhpUnhandledExceptionInspection */
        $user = $this->customers()->authenticate('test', 'test');

        $this->assertInstanceOf(UserDetailsInterface::class, $user);
    }

    public function testAuthenticateWith503Response()
    {
        $this->expectException(BackendUnavailable::class);

        /** @noinspection PhpUnhandledExceptionInspection */
        $this->customers($this->createServerException())->authenticate('test', 'test');
    }

    public function testAuthenticateWith400Response()
    {
        $this->expectException(BadCredentials::class);

        /** @noinspection PhpUnhandledExceptionInspection */
        $this->customers($this->createForbiddenException())->authenticate('test', 'test');
    }

    public function testUpdatePassword()
    {
        /** @noinspection PhpUnhandledExceptionInspection */
        $user = $this->customers()->updatePassword(new PasswordUpdate('123456', '654321'));

        $this->assertInstanceOf(UserDetailsInterface::class, $user);
    }

    public function testUpdatePasswordInvalid()
    {
        $this->expectException(InvalidInput::class);

        /** @noinspection PhpUnhandledExceptionInspection */
        $this->customers()->updatePassword(new PasswordUpdate('123456', 'short'));
    }

    public function testUpdatePasswordWith503Response()
    {
        $this->expectException(BackendUnavailable::class);

        /** @noinspection PhpUnhandledExceptionInspection */
        $this->customers($this->createServerException())->updatePassword(new PasswordUpdate('123456', '654321'));
    }

    public function testUpdatePasswordWith403Response()
    {
        $this->expectException(BadCredentials::class);

        /** @noinspection PhpUnhandledExceptionInspection */
        $this->customers($this->createForbiddenException())->updatePassword(new PasswordUpdate('123456', '654321'));
    }

    public function testStartPasswordReset()
    {
        /** @noinspection PhpUnhandledExceptionInspection */
        $this->customers()->startPasswordReset('test@astina.io');

        $this->assertTrue(true);
    }

    public function testStartPasswordResetInvalid()
    {
        $this->expectException(InvalidInput::class);

        /** @noinspection PhpUnhandledExceptionInspection */
        $this->customers()->startPasswordReset('invalid');
    }

    public function testStartPasswordResetWith503Response()
    {
        $this->expectException(BackendUnavailable::class);

        /** @noinspection PhpUnhandledExceptionInspection */
        $this->customers($this->createServerException())->startPasswordReset('test@astina.io');
    }

    public function testStartPasswordResetWith400Response()
    {
        $this->expectException(InvalidInput::class);

        /** @noinspection PhpUnhandledExceptionInspection */
        $this->customers($this->createBadRequestException())->startPasswordReset('test@astina.io');
    }

    public function testExistsPasswordResetToken()
    {
        /** @noinspection PhpUnhandledExceptionInspection */
        $exists = $this->customers()->existsPasswordResetToken('token');

        $this->assertTrue($exists);
    }

    public function testExistsPasswordResetTokenWith503Response()
    {
        $this->expectException(BackendUnavailable::class);

        /** @noinspection PhpUnhandledExceptionInspection */
        $this->customers($this->createServerException())->existsPasswordResetToken('token');
    }

    public function testExistsPasswordResetTokenWith404Response()
    {
        /** @noinspection PhpUnhandledExceptionInspection */
        $exists = $this->customers($this->createNotFoundException())->existsPasswordResetToken('token');

        $this->assertFalse($exists);
    }

    public function testUpdatePasswordWithResetToken()
    {
        /** @noinspection PhpUnhandledExceptionInspection */
        $user = $this->customers()->updatePasswordWithResetToken(new PasswordReset(
            'token',
            '123456'
        ));

        $this->assertInstanceOf(UserDetailsInterface::class, $user);
    }

    public function testUpdatePasswordWithResetTokenInvalid()
    {
        $this->expectException(InvalidInput::class);

        /** @noinspection PhpUnhandledExceptionInspection */
        $this->customers()->updatePasswordWithResetToken(new PasswordReset(
            'token',
            'short'
        ));
    }

    public function testUpdatePasswordWithResetTokenWith503Response()
    {
        $this->expectException(BackendUnavailable::class);

        /** @noinspection PhpUnhandledExceptionInspection */
        $this->customers($this->createServerException())->updatePasswordWithResetToken(new PasswordReset(
            'token',
            '123456'
        ));
    }

    public function testUpdatePasswordWithResetTokenWith400Response()
    {
        $this->expectException(InvalidConfirmationToken::class);

        /** @noinspection PhpUnhandledExceptionInspection */
        $this->customers($this->createBadRequestException())->updatePasswordWithResetToken(new PasswordReset(
            'token',
            '123456'
        ));
    }

    public function testGetOrders()
    {
        /** @noinspection PhpUnhandledExceptionInspection */
        $orders = $this->customers()->getOrders();

        $this->assertInstanceOf(OrderPageInterface::class, $orders);
    }

    public function testGetOrdersWith503Response()
    {
        $this->expectException(BackendUnavailable::class);

        /** @noinspection PhpUnhandledExceptionInspection */
        $this->customers($this->createServerException())->getOrders();
    }

    public function testGetOrdersWith400Response()
    {
        $this->expectException(InvalidInput::class);

        /** @noinspection PhpUnhandledExceptionInspection */
        $this->customers($this->createBadRequestException())->getOrders();
    }

    public function testGetOrder()
    {
        /** @noinspection PhpUnhandledExceptionInspection */
        $order = $this->customers()->getOrder('123');

        $this->assertInstanceOf(OrderInterface::class, $order);
    }

    public function testGetOrderWith503Response()
    {
        $this->expectException(BackendUnavailable::class);

        /** @noinspection PhpUnhandledExceptionInspection */
        $this->customers($this->createServerException())->getOrder('123');
    }

    public function testGetOrderWith404Response()
    {
        $this->expectException(OrderNotFound::class);

        /** @noinspection PhpUnhandledExceptionInspection */
        $this->customers($this->createNotFoundException())->getOrder('123');
    }

    public function testAddOrderItemsToCart()
    {
        /** @noinspection PhpUnhandledExceptionInspection */
        $results = $this->customers()->addOrderItemsToCart('123');

        $this->assertNotNull($results);
        $this->assertEquals('failed', $results->getStatus());
        $this->assertEquals(1, count($results->getItems()));
        $this->assertEquals('failed', $results->getItems()[0]->getStatus());
        $this->assertNotNull($results->getItems()[0]->getOrderItem());
        $this->assertNull($results->getItems()[0]->getCartItem());
    }

    private function validator(): ValidatorInterface
    {
        return Validation::createValidatorBuilder()
            ->addYamlMapping(__DIR__ . '/../../../../src/Paloma/Shop/Resources/validation.yaml')
            ->getValidator();
    }

    private function customers(Exception $exception = null): Customers
    {
        return new Customers(
            (new PalomaTestClient())->withCustomers(new CustomersTestClient($exception)),
            new TestUserProvider(),
            new PalomaTestConfig(),
            $this->validator()
        );
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

    /**
     * @return BadResponseException
     */
    private function createForbiddenException(): BadResponseException
    {
        return new BadResponseException(
            'test',
            new Request('GET', 'https://example.org'),
            new Response(403)
        );
    }
}