<?php

namespace Paloma\Shop\Customers;

use GuzzleHttp\Exception\BadResponseException;
use GuzzleHttp\Exception\ServerException;
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
use Symfony\Component\Validator\Validator\ValidatorInterface;

class Customers implements CustomersInterface
{
    /**
     * @var PalomaClientInterface
     */
    private $client;

    /**
     * @var ValidatorInterface
     */
    private $validator;

    /**
     * @var UserProviderInterface
     */
    private $userProvider;

    /**
     * @var PalomaConfigInterface
     */
    private $config;

    public function __construct(PalomaClientInterface $client,
                                ValidatorInterface $validator,
                                UserProviderInterface $userProvider,
                                PalomaConfigInterface $config)
    {
        $this->client = $client;
        $this->validator = $validator;
        $this->userProvider = $userProvider;
        $this->config = $config;
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

        throw new InvalidInput();
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
        $validation = $this->validator->validate($update);
        if ($validation->count() > 0) {
            throw InvalidInput::ofValidation($validation);
        }

        try {

            $data = $this->client->customers()->updateUserPassword([
                'username' => $this->userProvider->getUser()->getUsername(),
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

    function startPasswordReset(PasswordResetDraftInterface $draft): void
    {
        $validation = $this->validator->validate($draft);
        if ($validation->count() > 0) {
            throw InvalidInput::ofValidation($validation);
        }

        try {

            $this->client->customers()->startUserPasswordReset(
                $draft->getEmailAddress(),
                $draft->getConfirmationBaseUrl()
            );

        } catch (ServerException $se) {
            throw new BackendUnavailable();
        } catch (BadResponseException $bre) {
            throw new InvalidInput();
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
            throw new InvalidInput();
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
}