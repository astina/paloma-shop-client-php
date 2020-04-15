<?php

namespace Paloma\Shop\Customers;

use Paloma\Shop\Common\AddressInterface;
use Paloma\Shop\Error\BackendUnavailable;
use Paloma\Shop\Error\BadCredentials;
use Paloma\Shop\Error\CustomerNotFound;
use Paloma\Shop\Error\InvalidConfirmationToken;
use Paloma\Shop\Error\InvalidInput;
use Paloma\Shop\Error\NotAuthenticated;
use Paloma\Shop\Error\OrderNotFound;
use Paloma\Shop\Security\UserDetailsInterface;

interface CustomersInterface
{
    /**
     * Create a new customer and user account. The email address will be used as identifier within the current channel.
     *
     * @param CustomerDraftInterface $draft
     * @return UserDetailsInterface The created user and customer basics
     * @throws InvalidInput
     * @throws BackendUnavailable
     */
    function registerCustomer(CustomerDraftInterface $draft): UserDetailsInterface;

    /**
     * @return CustomerInterface The customer for the current user
     * @throws NotAuthenticated
     * @throws CustomerNotFound
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
     * @param string $emailAddress
     * @return CustomerInterface
     * @throws NotAuthenticated
     * @throws InvalidInput
     * @throws BackendUnavailable
     */
    function updateEmailAddress(string $emailAddress): CustomerInterface;

    /**
     * @param string $confirmationToken
     * @return UserDetailsInterface
     * @throws InvalidConfirmationToken
     * @throws BackendUnavailable
     */
    function confirmEmailAddress(string $confirmationToken): void;

    /**
     * @param string $emailAddress
     * @return bool True if a customer exists with the given email address
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
     * @param PasswordUpdateInterface $update
     * @return UserDetailsInterface
     * @throws NotAuthenticated
     * @throws BadCredentials
     * @throws InvalidInput
     * @throws BackendUnavailable
     */
    function updatePassword(PasswordUpdateInterface $update): UserDetailsInterface;

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
     * @param PasswordResetInterface $passwordReset
     * @return UserDetailsInterface
     * @throws InvalidInput
     * @throws InvalidConfirmationToken
     * @throws BackendUnavailable
     */
    function updatePasswordWithResetToken(PasswordResetInterface $passwordReset): UserDetailsInterface;

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

    /**
     * Adds all SKUs from the given order to the cart, provided they are still
     * available in the catalog.
     *
     * @param string $orderNumber
     * @return OrderRepetitionResultInterface
     * @throws NotAuthenticated
     * @throws OrderNotFound
     * @throws BackendUnavailable
     */
    function addOrderItemsToCart(string $orderNumber): OrderRepetitionResultInterface;

    /**
     * Returns all products the customer has purchased.
     *
     * @param int $page
     * @param int $size
     * @return CustomerProductPageInterface
     * @throws NotAuthenticated
     * @throws BackendUnavailable
     */
    function listProducts(int $page = 0, int $size = 20): CustomerProductPageInterface;

    /**
     * Returns all saved payment instruments for the current customer.
     *
     * @return CustomerPaymentInstrumentInterface[]
     * @throws NotAuthenticated
     * @throws BackendUnavailable
     */
    function listPaymentInstruments(): array;

    /**
     * Deletes the current customer's payment instrument with the given ID.
     *
     * @param $paymentInstrumentId
     * @throws NotAuthenticated
     * @throws BackendUnavailable
     */
    function deletePaymentInstrument($paymentInstrumentId): void;
}