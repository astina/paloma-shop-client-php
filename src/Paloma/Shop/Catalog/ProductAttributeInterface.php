<?php

namespace Paloma\Shop\Catalog;

/**
 * Attributes consist of a type, label and value. The type can be used as an identifier in case some
 * logic needs to be applied for certain attributes.
 */
interface ProductAttributeInterface
{
    /**
     * @return string Attribute type (e.g. "manufacturer_name")
     */
    function getType(): string;

    /**
     * @return string Attribute label, to be displayed to the user (e.g. "Manufacturer")
     */
    function getLabel(): string;

    /**
     * @return string Attribute value, to be displayed to the user (e.g. "Apple")
     */
    function getValue(): string;
}