<?php

namespace Paloma\Shop\Customers;

use Paloma\Shop\Common\AddressInterface;
use Paloma\Shop\Error\BackendUnavailable;
use Paloma\Shop\Error\BadCredentials;
use Paloma\Shop\Error\InvalidConfirmationToken;
use Paloma\Shop\Error\InvalidInput;
use Paloma\Shop\Error\NotAuthenticated;
use Paloma\Shop\Error\OrderNotFound;

interface CustomersInterface
{
    /**
     * Create a new customer and user account. The email address will be used as identifier within the current channel.
     *
     * @param CustomerDraftInterface $draft
     * @return CustomerInterface The created customer
     * @throws InvalidInput
     * @throws BackendUnavailable
     */
    function registerCustomer(CustomerDraftInterface $draft): CustomerInterface;

    /**
     * @return CustomerInterface The customer for the current user
     * @throws NotAuthenticated
     * @throws BackendUnavailable
     */
    function getCustomer(): CustomerInterface;

    /**
     * Update basic customer data for the current customer
     *
     * @param CustomerUpdateInterface $update
     * @return CustomerInterface
     * @throws NotAuthenticated
     * @throws InvalidInput
     * @throws BackendUnavailable
     */
    function updateCustomer(CustomerUpdateInterface $update): CustomerInterface;

    /**
     * Update preferred address for contact, billing or shipping.
     *
     * @param AddressUpdateInterface $update
     * @return AddressInterface
     * @throws NotAuthenticated
     * @throws InvalidInput
     * @throws BackendUnavailable
     */
    function updateAddress(AddressUpdateInterface $update): AddressInterface;

    /**
     * @param string $confirmationToken
     * @return UserDetailsInterface
     * @throws NotAuthenticated
     * @throws InvalidConfirmationToken
     * @throws BackendUnavailable
     */
    function confirmEmailAddress(string $confirmationToken): UserDetailsInterface;

    /**
     * @param string $emailAddress
     * @return bool True if a customer exists with the given email address
     * @throws NotAuthenticated
     * @throws BackendUnavailable
     */
    function existsCustomerByEmailAddress(string $emailAddress): bool;

    /**
     * @param string $username
     * @param string $password
     * @return UserDetailsInterface
     * @throws BadCredentials
     * @throws BackendUnavailable
     */
    function authenticate(string $username, string $password): UserDetailsInterface;

    /**
     * @param string $currentPassword
     * @param string $newPassword
     * @return UserDetailsInterface
     * @throws NotAuthenticated
     * @throws BadCredentials
     * @throws BackendUnavailable
     */
    function updatePassword(string $currentPassword, string $newPassword): UserDetailsInterface;

    /**
     * If the email address belongs to a known user, an email with a confirmation link is sent to the user.
     *
     * @param string $emailAddress
     * @throws InvalidInput
     * @throws BackendUnavailable
     */
    function startPasswordReset(string $emailAddress): void;

    /**
     * @param string $resetToken
     * @return bool True if the password reset token is valid
     * @throws BackendUnavailable
     */
    function existsPasswordResetToken(string $resetToken): bool;

    /**
     * @param string $resetToken
     * @param string $newPassword
     * @return UserDetailsInterface
     * @throws InvalidInput
     * @throws InvalidConfirmationToken
     * @throws BackendUnavailable
     */
    function updatePasswordWithResetToken(string $resetToken, string $newPassword): UserDetailsInterface;

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
    function getOrders(int $page = 0, int $size = 5, bool $orderDesc = true): OrderPageInterface;

    /**
     * Returns the customer's purchased order for the given order number.
     *
     * @param string $orderNumber
     * @return OrderInterface
     * @throws NotAuthenticated
     * @throws OrderNotFound
     * @throws BackendUnavailable
     */
    function getOrder(string $orderNumber): OrderInterface;
}