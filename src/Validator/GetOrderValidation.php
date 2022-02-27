<?php

namespace App\Validator;

use App\Util\PropertyTrait;
use Symfony\Component\Validator\Constraints as Assert;

final class GetOrderValidation implements \JsonSerializable
{
    use PropertyTrait;

    /**
     * @Assert\Type("integer")
     * @Assert\NotBlank
     */
    private $orderId;

    public function getOrderId(): string
    {
        return $this->orderId;
    }

    public function jsonSerialize()
    {
        return $this->toArray();
    }
}