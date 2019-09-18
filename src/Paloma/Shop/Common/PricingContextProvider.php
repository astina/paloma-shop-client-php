<?php

namespace Paloma\Shop\Common;

use Paloma\Shop\Error\BackendUnavailable;
use Paloma\Shop\Security\PalomaSecurityInterface;

class PricingContextProvider implements PricingContextProviderInterface
{
    /**
     * @var PalomaSecurityInterface
     */
    private $security;

    public function __construct(PalomaSecurityInterface $security)
    {
        $this->security = $security;
    }

    function provide(): PricingContext
    {
        $customer = null;
        try {
            $customer = $this->security->getCustomer();
        } catch (BackendUnavailable $ignore) {}

        if (!$customer) {
            return new PricingContext();
        }

        return new PricingContext($customer->getPriceGroups(), null, null);
    }
}