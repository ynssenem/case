<?php

namespace App\Validator;

use App\Util\PropertyTrait;
use Symfony\Component\Validator\Constraints as Assert;

final class UpdateOrderValidator implements \JsonSerializable
{
    use PropertyTrait;

    /**
     * @Assert\Type("integer")
     * @Assert\NotBlank
     */
    private $orderId;

    /**
     * @Assert\DateTime
     * @Assert\NotBlank
     */
    private $shippingDate;

    public function getOrderId(): string
    {
        return $this->orderId;
    }

    public function getShippingDate(): \DateTime
    {
        return new \DateTime($this->shippingDate);
    }

    public function jsonSerialize()
    {
        return $this->toArray();
    }
}