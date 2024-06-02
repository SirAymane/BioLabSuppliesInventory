<?php
namespace SirAymane\ecommerce\model;

use DateTime;

/**
 * Order model class representing an order in the ecommerce application.
 */
class Order {

    /**
     * @var int The unique identifier for the order.
     */
    private int $id;

    /**
     * @var DateTime The creation date and time of the order.
     */
    private DateTime $creationDate;

    /**
     * @var float The total amount of the order.
     */
    private float $totalAmount;

    /**
     * @var string The delivery method of the order.
     */
    private string $deliveryMethod;

    /**
     * @var int The user ID associated with the order.
     */
    private int $userId;

    /**
     * Constructor for the Order class.
     *
     * @param int $id The order's ID.
     * @param DateTime $creationDate The creation date and time of the order.
     * @param float $totalAmount The total amount of the order.
     * @param string $deliveryMethod The delivery method of the order.
     * @param int $userId The user ID associated with the order.
     */
    public function __construct(
        int $id, 
        DateTime $creationDate, 
        float $totalAmount,
        string $deliveryMethod,
        int $userId
    ) {
        $this->id = $id;
        $this->creationDate = $creationDate;
        $this->totalAmount = $totalAmount;
        $this->deliveryMethod = $deliveryMethod;
        $this->userId = $userId;
    }

    // Getters 

    /**
     * Gets the order's ID.
     *
     * @return int The order's ID.
     */
    public function getId(): int {
        return $this->id;
    }

    /**
     * Gets the order's creation date and time.
     *
     * @return DateTime The creation date and time of the order.
     */
    public function getCreationDate(): DateTime {
        return $this->creationDate;
    }

    /**
     * Gets the order's total amount.
     *
     * @return float The total amount of the order.
     */
    public function getTotalAmount(): float {
        return $this->totalAmount;
    }

    /**
     * Gets the order's delivery method.
     *
     * @return string The delivery method of the order.
     */
    public function getDeliveryMethod(): string {
        return $this->deliveryMethod;
    }

    /**
     * Gets the user ID associated with the order.
     *
     * @return int The user ID associated with the order.
     */
    public function getUserId(): int {
        return $this->userId;
    }

    // Setters

    /**
     * Sets the order's ID.
     *
     * @param int $id The order's ID.
     */
    public function setId(int $id): void {
        $this->id = $id;
    }

    /**
     * Sets the order's creation date and time.
     *
     * @param DateTime $creationDate The creation date and time of the order.
     */
    public function setCreationDate(DateTime $creationDate): void {
        $this->creationDate = $creationDate;
    }

    /**
     * Sets the order's total amount.
     *
     * @param float $totalAmount The total amount of the order.
     */
    public function setTotalAmount(float $totalAmount): void {
        $this->totalAmount = $totalAmount;
    }

    /**
     * Sets the order's delivery method.
     *
     * @param string $deliveryMethod The delivery method of the order.
     */
    public function setDeliveryMethod(string $deliveryMethod): void {
        $this->deliveryMethod = $deliveryMethod;
    }

    /**
     * Sets the user ID associated with the order.
     *
     * @param int $userId The user ID associated with the order.
     */
    public function setUserId(int $userId): void {
        $this->userId = $userId;
    }

    /**
     * Generates a string representation of the Order object.
     *
     * @return string The string representation of the order.
     */
    public function __toString(): string {
        return sprintf("Order{[id=%d][creationDate=%s][totalAmount=%.2f][deliveryMethod=%s][userId=%d]}",
                $this->id, $this->creationDate->format('Y-m-d H:i:s'), 
                $this->totalAmount, $this->deliveryMethod, $this->userId);
    }
}
