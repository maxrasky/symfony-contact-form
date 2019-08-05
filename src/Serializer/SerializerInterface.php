<?php

namespace App\Serializer;

/**
 * interface SerializerInterface
 * @package App\Serializer
 */
interface SerializerInterface
{
    public function serialize($data);

    public function deserialize($data, $type);
}
