<?php

namespace Paloma\Shop;

use Paloma\Shop\Catalog\CatalogClientInterface;

class Paloma
{
    /**
     * @var CatalogClientInterface
     */
    private $catalog;

    public function __construct(CatalogClientInterface $catalog)
    {
        $this->catalog = $catalog;
    }

    /**
     * @return CatalogClientInterface
     */
    public function catalog()
    {
        return $this->catalog;
    }
}