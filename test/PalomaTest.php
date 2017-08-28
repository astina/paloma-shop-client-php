<?php

namespace Paloma\Shop;

use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\Storage\MockFileSessionStorage;

class PalomaTest extends TestCase
{
    public function testInit()
    {
        $paloma = Paloma::create('https://demo.paloma.one/api/', '', new Session(new MockFileSessionStorage()));

//        $categories = $paloma->catalog()->categories('ch', 'de', 10);
//        $this->assertNotNull($categories);
//
//        $category = $paloma->catalog()->category('ch', 'de', 1, 10, true);
//
//        $categoryFilters = $paloma->catalog()->categoryFilters('ch', 'de', 1.5);
//
//        $product = $paloma->catalog()->product('ch', 'de', '128947');
//
//        $recommendedProducts = $paloma->catalog()->recommendedProducts('ch', 'de', '128ab947');
//
//        $similarProducts = $paloma->catalog()->similarProducts('ch', 'de', '128947');
//
//        $recommendations = $paloma->catalog()->recommendations('ch', 'de', ['id' => 123], 41);
//
//        $search = $paloma->catalog()->search('ch', 'de', ['category' => 123]);
//
//        $searchSuggestions = $paloma->catalog()->searchSuggestions('ch', 'de', 'shoe');


//        $createOrder = $paloma->checkout()->createOrder(['country' => 'ch', 'language' => 'de']);
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


//        $createAdvertisingPrefs = $paloma->customers()->createAdvertisingPrefs('ch', ['emailAddress' => 'admin@astina.ch']);
//
//        $confirmAdvertisingPrefs = $paloma->customers()->confirmAdvertisingPrefs('ch', 'myToken');
//
//        $authenticate = $paloma->customers()->authenticate('ch', ['emailAddress' => 'admin@astina.ch', 'password' => '1234']);
//
//        $getLoyaltyPrograms = $paloma->customers()->getLoyaltyPrograms('ch', 'admin@astina.ch');
//
//        $updateLoyaltyPrograms = $paloma->customers()->updateLoyaltyPrograms('ch', 'admin@astina.ch', ['code' => 'crew_member']);
//
//        $startPasswordReset = $paloma->customers()->startPasswordReset('ch', ['emailAddress' => 'admin@astina.ch']);
//
//        $getPasswordResetToken = $paloma->customers()->getPasswordResetToken('ch', 'myToken');
//
//        $updatePassword = $paloma->customers()->updatePassword('ch', 'myToken', ['password' => '1234']);
//
//        $register = $paloma->customers()->register('ch', ['emailAddress' => 'admin@astina.ch']);
//
//        $getUser = $paloma->customers()->getUser('ch', '987');
//
//        $updateUserPartially = $paloma->customers()->updateUserPartially('ch', '987', ['emailAddress' => 'admin@astina.ch']);
//
//        $updateUser = $paloma->customers()->updateUser('ch', '987', ['emailAddress' => 'admin@astina.ch']);
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