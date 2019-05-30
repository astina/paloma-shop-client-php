<?php

namespace Paloma\Shop\Common;

use Symfony\Component\Serializer\Annotation\SerializedName;

interface MetadataContainingObject
{
    /**
     * @SerializedName("_validation")
     */
    function getMetaValidation();
}