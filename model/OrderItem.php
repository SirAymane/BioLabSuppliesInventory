<?php

namespace SirAymane\ecommerce\model;

/**
 * Represents an item within an order in the eCommerce application.
 */
class OrderItem {
    private int $orderId;
    private int $productId;
    private int $quantity;
    private float $unitPrice;

    public function __construct(int $orderId, int $productId, int $quantity, float $unitPrice) {
        $this->orderId = $orderId;
        $this->productId = $productId;
        $this->quantity = $quantity;
        $this->unitPrice = $unitPrice;
    }

    public function getOrderId(): int {
        return $this->orderId;
    }

    public function getProductId(): int {
        return $this->productId;
    }

    public function getQuantity(): int {
        return $this->quantity;
    }

    public function getUnitPrice(): float {
        return $this->unitPrice;
    }

    public function getTotalPrice(): float {
        return $this->quantity * $this->unitPrice;
    }

    public function __toString(): string {
        return sprintf("OrderItem{[orderId=%d][productId=%d][quantity=%d][unitPrice=%.2f]}",
            $this->orderId, $this->productId, $this->quantity, $this->unitPrice);
    }
}
