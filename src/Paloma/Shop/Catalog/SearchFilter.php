<?php

namespace Paloma\Shop\Catalog;

class SearchFilter implements SearchFilterInterface
{
    private $name;

    private $values;

    private $greaterThan;

    private $lessThan;

    /**
     * @param string $name
     * @param string[] $values
     * @param float $greaterThan
     * @param float $lessThan
     */
    public function __construct(string $name, array $values = [], float $greaterThan = null, float $lessThan = null)
    {
        $this->name = $name;
        $this->values = array_values($values);
        $this->greaterThan = $greaterThan;
        $this->lessThan = $lessThan;
    }


    function getName(): string
    {
        return $this->name;
    }

    function getValues(): array
    {
        return $this->values;
    }

    function getGreaterThan(): ?float
    {
        return $this->greaterThan;
    }

    function getLessThan(): ?float
    {
        return $this->lessThan;
    }
}