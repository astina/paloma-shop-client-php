<?php

namespace Paloma\Shop\Customers;

use Egulias\EmailValidator\EmailValidator;
use Egulias\EmailValidator\Validation\RFCValidation;
use Exception;
use GuzzleHttp\Exception\BadResponseException;
use GuzzleHttp\Exception\ServerException;
use Paloma\Shop\Checkout\Cart;
use Paloma\Shop\Common\Address;
use Paloma\Shop\Common\AddressInterface;
use Paloma\Shop\Error\BackendUnavailable;
use Paloma\Shop\Error\BadCredentials;
use Paloma\Shop\Error\InvalidConfirmationToken;
use Paloma\Shop\Error\InvalidInput;
use Paloma\Shop\Error\NotAuthenticated;
use Paloma\Shop\Error\OrderNotFound;
use Paloma\Shop\PalomaClientInterface;
use Paloma\Shop\PalomaConfigInterface;
use Paloma\Shop\Security\UserDetails;
use Paloma\Shop\Security\UserDetailsInterface;
use Paloma\Shop\Security\UserProviderInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class Customers implements CustomersInterface
{
    /**
     * @var PalomaClientInterface
     */
    private $client;

    /**
     * @var UserProviderInterface
     */
    private $userProvider;

    /**
     * @var PalomaConfigInterface
     */
    private $config;

    /**
     * @var ValidatorInterface
     */
    private $validator;

    public function __construct(PalomaClientInterface $client,
                                UserProviderInterface $userProvider,
                                PalomaConfigInterface $config,
                                ValidatorInterface $validator)
    {
        $this->client = $client;
        $this->userProvider = $userProvider;
        $this->config = $config;
        $this->validator = $validator;
    }

    /**
     * @return string Customer Id
     * @throws NotAuthenticated
     */
    protected function getCustomerId(): string
    {
        $user = $this->userProvider->getUser();

        if ($user === null) {
            throw new NotAuthenticated();
        }

        return $user->getCustomerId();
    }

    function registerCustomer(CustomerDraftInterface $draft): CustomerInterface
    {
        $validation = $this->validator->validate($draft);
        if ($validation->count() > 0) {
            throw InvalidInput::ofValidation($validation);
        }

        try {

            $data = $this->client->customers()->register([
                'emailAddress' => $draft->getEmailAddress(),
                'confirmationBaseUrl' => $this->config->getRegistrationConfirmationBaseUrl(),
                'user' => [
                    'username' => $draft->getEmailAddress(),
                    'password' => $draft->getPassword(),
                ],
                'firstName' => $draft->getFirstName(),
                'lastName' => $draft->getLastName(),
                'locale' => $draft->getLocale(),
                'gender' => $draft->getGender(),
                'dateOfBirth' => $draft->getDateOfBirth() ? $draft->getDateOfBirth()->format('Y-m-d') : null,
            ]);

            return new Customer($data);

        } catch (ServerException $se) {
            throw new BackendUnavailable();
        } catch (BadResponseException $bre) {
            throw InvalidInput::ofHttpResponse($bre->getResponse());
        }
    }

    function getCustomer(): CustomerInterface
    {
        try {

            $data = $this->client->customers()->getCustomer($this->getCustomerId());

            return new Customer($data);

        } catch (ServerException $se) {
            throw new BackendUnavailable();
        }
    }

    function updateCustomer(CustomerUpdateInterface $update): CustomerInterface
    {
        $validation = $this->validator->validate($update);
        if ($validation->count() > 0) {
            throw InvalidInput::ofValidation($validation);
        }

        try {

            $data = $this->client->customers()->updateCustomer($this->getCustomerId(), [
                'emailAddress' => $update->getEmailAddress(),
                'firstName' => $update->getFirstName(),
                'lastName' => $update->getLastName(),
                'locale' => $update->getLocale(),
                'gender' => $update->getGender(),
                'dateOfBirth' => $update->getDateOfBirth() ? $update->getDateOfBirth()->format('Y-m-d') : null,
            ]);

            return new Customer($data);

        } catch (ServerException $se) {
            throw new BackendUnavailable();
        } catch (BadResponseException $bre) {
            throw InvalidInput::ofHttpResponse($bre->getResponse());
        }
    }

    function updateAddress(AddressUpdateInterface $update): AddressInterface
    {
        $validation = $this->validator->validate($update);
        if ($validation->count() > 0) {
            throw InvalidInput::ofValidation($validation);
        }

        try {

            $data = $this->client->customers()->updateAddress(
                $this->getCustomerId(),
                $update->getAddressType(),
                Address::toAddressArray($update)
            );

        } catch (ServerException $se) {
            throw new BackendUnavailable();
        } catch (BadResponseException $bre) {
            throw InvalidInput::ofHttpResponse($bre->getResponse());
        }

        $customer = new Customer($data);

        switch ($update->getAddressType()) {
            case 'contact':
                return $customer->getContactAddress();
            case 'billing':
                return $customer->getBillingAddress();
            case 'shipping':
                return $customer->getShippingAddress();
        }

        throw new InvalidInput(['property' => 'addressType', 'message' => 'Unknown address type']);
    }

    function confirmEmailAddress(string $confirmationToken): UserDetailsInterface
    {
        try {

            $this->client->customers()->confirmEmailAddress($confirmationToken);

            return $this->userProvider->getUser();

        } catch (ServerException $se) {
            throw new BackendUnavailable();
        } catch (BadResponseException $se) {
            throw new InvalidConfirmationToken();
        }
    }

    function existsCustomerByEmailAddress(string $emailAddress): bool
    {
        try {

            $this->client->customers()->exists($emailAddress);

            return true;

        } catch (ServerException $se) {
            throw new BackendUnavailable();
        } catch (BadResponseException $se) {
            return false;
        }
    }

    function authenticate(string $username, string $password): UserDetailsInterface
    {
        try {

            $data = $this->client->customers()->authenticateUser($username, $password);

            return new UserDetails($data);

        } catch (ServerException $se) {
            throw new BackendUnavailable();
        } catch (BadResponseException $se) {
            throw new BadCredentials();
        }
    }

    function updatePassword(PasswordUpdateInterface $update): UserDetailsInterface
    {
        $user = $this->userProvider->getUser();
        if ($user === null) {
            throw new NotAuthenticated();
        }

        $validation = $this->validator->validate($update);
        if ($validation->count() > 0) {
            throw InvalidInput::ofValidation($validation);
        }

        try {

            $data = $this->client->customers()->updateUserPassword([
                'username' => $user->getUsername(),
                'currentPassword' => $update->getCurrentPassword(),
                'newPassword' => $update->getNewPassword(),
            ]);

            return new UserDetails($data);

        } catch (ServerException $se) {
            throw new BackendUnavailable();
        } catch (BadResponseException $bre) {
            if ($bre->getCode() === 403) {
                throw new BadCredentials();
            }
            throw InvalidInput::ofHttpResponse($bre->getResponse());
        }
    }

    function startPasswordReset(string $emailAddress): void
    {
        if (!(new EmailValidator())->isValid($emailAddress, new RFCValidation())) {
            throw new InvalidInput( ['property' => 'emailAddress', 'message' => 'Invalid email address']);
        }

        try {

            $this->client->customers()->startUserPasswordReset(
                $emailAddress,
                $this->config->getPasswordResetConfirmationBaseUrl()
            );

        } catch (ServerException $se) {
            throw new BackendUnavailable();
        } catch (BadResponseException $bre) {
            throw InvalidInput::ofHttpResponse($bre->getResponse());
        }
    }

    function existsPasswordResetToken(string $resetToken): bool
    {
        try {

            $this->client->customers()->getUserPasswordResetToken($resetToken);

            return true;

        } catch (ServerException $se) {
            throw new BackendUnavailable();
        } catch (BadResponseException $bre) {
            return false;
        }
    }

    function updatePasswordWithResetToken(PasswordResetInterface $passwordReset): UserDetailsInterface
    {
        $validation = $this->validator->validate($passwordReset);
        if ($validation->count() > 0) {
            throw InvalidInput::ofValidation($validation);
        }

        try {

            $data = $this->client->customers()->finishUserPasswordReset(
                $passwordReset->getToken(),
                $passwordReset->getNewPassword());

            return new UserDetails($data);

        } catch (ServerException $se) {
            throw new BackendUnavailable();
        } catch (BadResponseException $bre) {
            throw new InvalidConfirmationToken();
        }
    }

    function getOrders(int $page = 0, int $size = 5, bool $orderDesc = true): OrderPageInterface
    {
        try {

            $data = $this->client->customers()->getOrders(
                $this->getCustomerId(),
                $page,
                $size,
                $orderDesc ? 'orderDate,desc' : 'orderDate,asc'
            );

            return new OrderPage($data);

        } catch (ServerException $se) {
            throw new BackendUnavailable();
        } catch (BadResponseException $bre) {
            throw InvalidInput::ofHttpResponse($bre->getResponse());
        }
    }

    function getOrder(string $orderNumber): OrderInterface
    {
        try {

            $data = $this->client->customers()->getOrder(
                $this->getCustomerId(),
                $orderNumber
            );

            return new Order($data);

        } catch (ServerException $se) {
            throw new BackendUnavailable();
        } catch (BadResponseException $bre) {
            throw new OrderNotFound();
        }
    }

    function addOrderItemsToCart(string $orderNumber): OrderRepetitionResultInterface
    {
        $order = $this->getOrder($orderNumber);

        $itemResults = [];

        foreach ($order->getItems() as $orderItem) {

            try {

                $data = $this->client->checkout()->checkoutOrder()->addItem(
                    $orderItem->getSku(),
                    $orderItem->getQuantity()
                );

                $cart = new Cart($data);

                foreach ($cart->getItems() as $cartItem) {
                    if ($cartItem->getSku() === $orderItem->getSku()) {
                        $itemResults[] = new OrderRepetitionResultItem(
                            OrderRepetitionResultItem::STATUS_SUCCESS,
                            $orderItem,
                            $cartItem
                        );
                        break;
                    }
                }

            } catch (Exception $e) {
                $itemResults[] = new OrderRepetitionResultItem(
                    OrderRepetitionResultItem::STATUS_FAILED,
                    $orderItem
                );
            }
        }

        return new OrderRepetitionResult($itemResults);
    }
}