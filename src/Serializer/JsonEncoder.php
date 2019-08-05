<?php

namespace App\Serializer;

use Symfony\Component\Serializer\NameConverter\CamelCaseToSnakeCaseNameConverter;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

/**
 * Class Serializer
 * @package App
 */
class JsonEncoder implements SerializerInterface
{
    private $serializer;

    public function __construct()
    {
        $normalizers = [new ObjectNormalizer(null, new CamelCaseToSnakeCaseNameConverter())];
        $encoders = [new \Symfony\Component\Serializer\Encoder\JsonEncoder()];

        $this->serializer = new Serializer($normalizers, $encoders);
    }

    public function serialize($data)
    {
        return $this->serializer->serialize($data, 'json');
    }

    public function deserialize($data, $type)
    {
        return $this->serializer->deserialize($data, $type, 'json');
    }
}
