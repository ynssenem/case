<?php

namespace App\Validator;

use App\Util\PropertyTrait;
use Symfony\Component\Validator\Constraints as Assert;

final class CreateOrderValidator implements \JsonSerializable
{
    use PropertyTrait;

    /**
     * @Assert\Type("string")
     * @Assert\NotBlank
     */
    private $orderCode;

    /**
     * @Assert\Type("integer")
     * @Assert\NotBlank
     */
    private $productId;

    /**
     * @Assert\Type("integer")
     * @Assert\NotBlank
     */
    private $quantity;

    /**
     * @Assert\Type("string")
     * @Assert\NotBlank
     */
    private $address;

    /**
     * @Assert\DateTime
     */
    private $shippingDate;

    public function getOrderCode(): string
    {
        return $this->orderCode;
    }

    public function getProductId(): int
    {
        return $this->productId;
    }

    public function getQuantity(): int
    {
        return $this->quantity;
    }

    public function getAddress(): string
    {
        return $this->address;
    }

    public function getShippingDate(): ?\DateTime
    {
        if ($this->shippingDate) {
            return new \DateTime($this->shippingDate);
        }

        return null;
    }

    public function jsonSerialize()
    {
        return $this->toArray();
    }
}