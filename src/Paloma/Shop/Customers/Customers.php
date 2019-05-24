<?php

namespace Paloma\Shop\Customers;

use Egulias\EmailValidator\EmailValidator;
use Egulias\EmailValidator\Validation\RFCValidation;
use Exception;
use GuzzleHttp\Exception\BadResponseException;
use GuzzleHttp\Exception\ServerException;
use GuzzleHttp\Exception\TransferException;
use Paloma\Shop\Checkout\Cart;
use Paloma\Shop\Checkout\CheckoutOrder;
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
use Paloma\Shop\Security\PalomaSecurityInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class Customers implements CustomersInterface
{
    /**
     * @var PalomaClientInterface
     */
    private $client;

    /**
     * @var PalomaSecurityInterface
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
                                PalomaSecurityInterface $userProvider,
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

    function registerCustomer(CustomerDraftInterface $draft): UserDetailsInterface
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
                'company' => $draft->getCompany(),
                'locale' => $draft->getLocale(),
                'gender' => $draft->getGender(),
                'dateOfBirth' => $draft->getDateOfBirth() ? $draft->getDateOfBirth()->format('Y-m-d') : null,
            ]);

            return new UserDetails([
                'user' => $data['users'][0],
                'customer' => $data,
            ]);

        } catch (BadResponseException $bre) {
            throw InvalidInput::ofHttpResponse($bre->getResponse());
        } catch (TransferException $se) {
            throw BackendUnavailable::ofException($se);
        }
    }

    function getCustomer(): CustomerInterface
    {
        try {

            $customer = $this->userProvider->getCustomer();

            if ($customer == null) {
                throw new NotAuthenticated();
            }

            return $customer;

        } catch (TransferException $se) {
            throw BackendUnavailable::ofException($se);
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
                'company' => $update->getCompany(),
                'locale' => $update->getLocale(),
                'gender' => $update->getGender(),
                'dateOfBirth' => $update->getDateOfBirth() ? $update->getDateOfBirth()->format('Y-m-d') : null,
            ]);

            return new Customer($data);

        } catch (BadResponseException $bre) {
            throw InvalidInput::ofHttpResponse($bre->getResponse());
        } catch (TransferException $se) {
            throw BackendUnavailable::ofException($se);
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

        } catch (BadResponseException $bre) {
            throw InvalidInput::ofHttpResponse($bre->getResponse());
        } catch (TransferException $se) {
            throw BackendUnavailable::ofException($se);
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

        } catch (BadResponseException $se) {
            throw new InvalidConfirmationToken();
        } catch (TransferException $se) {
            throw BackendUnavailable::ofException($se);
        }
    }

    function existsCustomerByEmailAddress(string $emailAddress): bool
    {
        try {

            $this->client->customers()->exists($emailAddress);

            return true;

        } catch (BadResponseException $se) {
            return false;
        } catch (TransferException $se) {
            throw BackendUnavailable::ofException($se);
        }
    }

    function authenticate(string $username, string $password): UserDetailsInterface
    {
        try {

            $data = $this->client->customers()->authenticateUser($username, $password);

            return new UserDetails($data);

        } catch (BadResponseException $se) {
            throw new BadCredentials();
        } catch (TransferException $se) {
            throw BackendUnavailable::ofException($se);
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

        } catch (BadResponseException $bre) {
            if ($bre->getCode() === 403) {
                throw new BadCredentials();
            }
            throw InvalidInput::ofHttpResponse($bre->getResponse());
        } catch (TransferException $se) {
            throw BackendUnavailable::ofException($se);
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

        } catch (BadResponseException $bre) {
            throw InvalidInput::ofHttpResponse($bre->getResponse());
        } catch (TransferException $se) {
            throw BackendUnavailable::ofException($se);
        }
    }

    function existsPasswordResetToken(string $resetToken): bool
    {
        try {

            $this->client->customers()->getUserPasswordResetToken($resetToken);

            return true;

        } catch (BadResponseException $bre) {
            return false;
        } catch (TransferException $se) {
            throw BackendUnavailable::ofException($se);
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

        } catch (BadResponseException $bre) {
            throw new InvalidConfirmationToken();
        } catch (TransferException $se) {
            throw BackendUnavailable::ofException($se);
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

        } catch (BadResponseException $bre) {
            throw InvalidInput::ofHttpResponse($bre->getResponse());
        } catch (TransferException $se) {
            throw BackendUnavailable::ofException($se);
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

        } catch (BadResponseException $bre) {
            throw new OrderNotFound();
        } catch (TransferException $se) {
            throw BackendUnavailable::ofException($se);
        }
    }

    function addOrderItemsToCart(string $orderNumber): OrderRepetitionResultInterface
    {
        $order = $this->getOrder($orderNumber);

        $itemResults = [];

        foreach ($order->getItems() as $orderItem) {

            try {

                $data = $this->getCheckoutOrder()->addItem(
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

    /**
     * @return CheckoutOrder
     * @throws BackendUnavailable
     * @throws NotAuthenticated
     */
    private function getCheckoutOrder(): CheckoutOrder
    {
        return $this->client->checkout()->checkoutOrder($this->getCustomer(), $this->userProvider->getUser());
    }
}