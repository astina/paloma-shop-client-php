<?php

namespace Paloma\Shop;

use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\Storage\MockFileSessionStorage;

class PalomaTest extends TestCase
{
    public function testInit()
    {
        $paloma = Paloma::create('https://demo.paloma.one/api/', 'apikey', 'demo_b2c', 'de',
            new Session(new MockFileSessionStorage()),
            null, null, null,
            null,
            null,
            '7429cb0d9');

//        $categories = $paloma->catalog()->categories(10);
//        $this->assertNotNull($categories);
//
//        $category = $paloma->catalog()->category(1, 10, true);
//
//        $categoryFilters = $paloma->catalog()->categoryFilters(1.5);
//
//        $product = $paloma->catalog()->product('128947');
//
//        $recommendedProducts = $paloma->catalog()->recommendedProducts('128ab947');
//
//        $similarProducts = $paloma->catalog()->similarProducts('128947');
//
//        $recommendations = $paloma->catalog()->recommendations(['id' => 123], 41);
//
//        $search = $paloma->catalog()->search(['category' => 123]);
//
//        $searchSuggestions = $paloma->catalog()->searchSuggestions('shoe');


//        $createOrder = $paloma->checkout()->createOrder(['channel' => 'demo_b2c']);
//
//        $deleteOrder = $paloma->checkout()->deleteOrder('123');
//
//        $getOrder = $paloma->checkout()->getOrder('123', 'ch');
//
//        $setAddresses = $paloma->checkout()->setAddresses('123', ['billingAddress' => ['title' => 'Mr.']]);
//
//        $addCoupon = $paloma->checkout()->addCoupon('123', ['code' => 'CHJX97JS89']);
//
//        $deleteCoupon = $paloma->checkout()->deleteCoupon('123','CHJX97JS89');
//
//        $finalizeOrder = $paloma->checkout()->finalizeOrder('123');
//
//        $addOrderItem = $paloma->checkout()->addOrderItem('123', ['sku' => '456da']);
//
//        $setPaymentMethod = $paloma->checkout()->setPaymentMethod('123', ['name' => 'paypal']);
//
//        $purchaseOrder = $paloma->checkout()->purchaseOrder('123');
//
//        $setShippingMethod = $paloma->checkout()->setShippingMethod('123', ['name' => 'post_priority']);
//
//        $setUser = $paloma->checkout()->setUser('123', ['id' => '123']);
//
//        $deleteOrderItem = $paloma->checkout()->deleteOrderItem('123', '321');
//
//        $updateOrderItem = $paloma->checkout()->updateOrderItem('123', '321', ['quantity' => 2]);
//
//        $numOfOrderItems = $paloma->checkout()->orderItemQuantity('123');
//
//        $getPaymentMethods = $paloma->checkout()->getPaymentMethods('123');
//
//        $initializePayment = $paloma->checkout()->initializePayment(['order' => '123']);
//
//        $getShippingMethods = $paloma->checkout()->getShippingMethods('123');
//
//        $createAdvertisingPrefs = $paloma->customers()->createAdvertisingPrefs('ch', ['emailAddress' => 'test@astina.io']);
//
//        $confirmAdvertisingPrefs = $paloma->customers()->confirmAdvertisingPrefs('ch', 'myToken');
//
//        $authenticate = $paloma->customers()->authenticate('ch', ['emailAddress' => 'test@astina.io', 'password' => '1234']);
//
//        $getLoyaltyPrograms = $paloma->customers()->getLoyaltyPrograms('ch', 'test@astina.io');
//
//        $updateLoyaltyPrograms = $paloma->customers()->updateLoyaltyPrograms('ch', 'test@astina.io', ['code' => 'crew_member']);
//
//        $startPasswordReset = $paloma->customers()->startPasswordReset('ch', ['emailAddress' => 'test@astina.io']);
//
//        $getPasswordResetToken = $paloma->customers()->getPasswordResetToken('ch', 'myToken');
//
//        $updatePassword = $paloma->customers()->updatePassword('ch', 'myToken', ['password' => '1234']);
//
//        $register = $paloma->customers()->register('ch', ['emailAddress' => 'test@astina.io']);
//
//        $getUser = $paloma->customers()->getUser('ch', '987');
//
//        $updateUserPartially = $paloma->customers()->updateUserPartially('ch', '987', ['emailAddress' => 'test@astina.io']);
//
//        $updateUser = $paloma->customers()->updateUser('ch', '987', ['emailAddress' => 'test@astina.io']);
//
//        $getWishList = $paloma->customers()->getWishList('ch', '987');
//
//        $addWishListItem = $paloma->customers()->addWishListItem('ch', '987', ['itemNumber' => '3321ab']);
//
//        $deleteWishListItem = $paloma->customers()->deleteWishListItem('ch', '987', 1235);
//
//        $getOrderStatus = $paloma->customers()->getOrderStatus('ch', 'de', '23987-12');
//
//        $getOrders = $paloma->customers()->getOrders('ch', 'de', '987', 0, 20, 'DESC');
//
//        $getOrder = $paloma->customers()->getOrder('ch', 'de', '987', '23987-12');
//
//        $getOrderReceipt = $paloma->customers()->getOrderReceipt('ch', 'de', '987', '23987-12');
    }
}