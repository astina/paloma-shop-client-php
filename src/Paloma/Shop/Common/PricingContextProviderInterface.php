<?php

namespace Paloma\Shop\Common;

interface PricingContextProviderInterface
{
    function provide(): PricingContext;
}