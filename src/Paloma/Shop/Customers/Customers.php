<?php

namespace Paloma\Shop\Customers;

use Exception;
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

    public function __construct(PalomaClientInterface $client, ValidatorInterface $validator)
    {
        $this->client = $client;
        $this->validator = $validator;
    }

    protected function getCustomerId(): string
    {
        return 'TODO'; // TODO implement
    }

    function registerCustomer(CustomerDraftInterface $draft): CustomerInterface
    {
        $validation = $this->validator->validate($draft);
        if ($validation->count() > 0) {
            throw new InvalidInput($validation);
        }

        try {

            $data = $this->client->customers()->register([
                'emailAddress' => $draft->getEmailAddress(),
                'confirmationBaseUrl' => $draft->getConfirmationBaseUrl(),
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
        } catch (BadResponseException $se) {
            throw new InvalidInput(); // TODO validation info
        }
    }

    function getCustomer(): CustomerInterface
    {
        // TODO throw NotAuthenticated

        try {

            $data = $this->client->customers()->getCustomer($this->getCustomerId());

            return new Customer($data);

        }  catch (ServerException $se) {
            throw new BackendUnavailable();
        }
    }

    function updateCustomer(CustomerUpdateInterface $update): CustomerInterface
    {
        // TODO throw NotAuthenticated

        $validation = $this->validator->validate($update);
        if ($validation->count() > 0) {
            throw new InvalidInput($validation);
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
        } catch (BadResponseException $se) {
            throw new InvalidInput(); // TODO validation info
        }
    }

    function updateAddress(AddressUpdateInterface $update): AddressInterface
    {
        // TODO throw NotAuthenticated

        $validation = $this->validator->validate($update);
        if ($validation->count() > 0) {
            throw new InvalidInput($validation);
        }

        try {

            $data = $this->client->customers()->updateAddress(
                $this->getCustomerId(),
                $update->getAddressType(),
                Address::toAddressArray($update)
            );

        } catch (ServerException $se) {
            throw new BackendUnavailable();
        } catch (BadResponseException $se) {
            throw new InvalidInput(); // TODO validation info
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
        $this->client->customers()->confirmEmailAddress($confirmationToken);
    }

    /**
     * @param string $emailAddress
     * @return bool True if a customer exists with the given email address
     * @throws NotAuthenticated
     * @throws BackendUnavailable
     */
    function existsCustomerByEmailAddress(string $emailAddress): bool
    {
        // TODO: Implement existsCustomerByEmailAddress() method.
    }

    /**
     * @param string $username
     * @param string $password
     * @return UserDetailsInterface
     * @throws BadCredentials
     * @throws BackendUnavailable
     */
    function authenticate(string $username, string $password): UserDetailsInterface
    {
        // TODO: Implement authenticate() method.
    }

    /**
     * @param string $currentPassword
     * @param string $newPassword
     * @return UserDetailsInterface
     * @throws NotAuthenticated
     * @throws BadCredentials
     * @throws BackendUnavailable
     */
    function updatePassword(string $currentPassword, string $newPassword): UserDetailsInterface
    {
        // TODO: Implement updatePassword() method.
    }

    /**
     * If the email address belongs to a known user, an email with a confirmation link is sent to the user.
     *
     * @param string $emailAddress
     * @throws NotAuthenticated
     * @throws InvalidInput
     * @throws BackendUnavailable
     */
    function startPasswordReset(string $emailAddress): void
    {
        // TODO: Implement startPasswordReset() method.
    }

    /**
     * @param string $resetToken
     * @return bool True if the password reset token is valid
     * @throws NotAuthenticated
     * @throws BackendUnavailable
     */
    function existsPasswordResetToken(string $resetToken): bool
    {
        // TODO: Implement existsPasswordResetToken() method.
    }

    /**
     * @param string $resetToken
     * @param string $newPassword
     * @return UserDetailsInterface
     * @throws NotAuthenticated
     * @throws InvalidInput
     * @throws InvalidConfirmationToken
     * @throws BackendUnavailable
     */
    function updatePasswordWithResetToken(string $resetToken, string $newPassword): UserDetailsInterface
    {
        // TODO: Implement updatePasswordWithResetToken() method.
    }

    /**
     * Returns the customer's purchased orders, sorted date.
     *
     * @param int $page
     * @param int $size
     * @param bool $orderDesc
     * @return OrderPageInterface
     * @throws NotAuthenticated
     * @throws InvalidInput
     * @throws BackendUnavailable
     */
    function getOrders(int $page = 0, int $size = 5, bool $orderDesc = true): OrderPageInterface
    {
        // TODO: Implement getOrders() method.
    }

    /**
     * Returns the customer's purchased order for the given order number.
     *
     * @param string $orderNumber
     * @return OrderInterface
     * @throws NotAuthenticated
     * @throws OrderNotFound
     * @throws BackendUnavailable
     */
    function getOrder(string $orderNumber): OrderInterface
    {
        // TODO: Implement getOrder() method.
    }
}