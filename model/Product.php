<?php
namespace SirAymane\ecommerce\model;

/**
 * Product model class representing a product in the ecommerce application.
 */
class Product {

    /**
     * @var int The unique identifier for the product.
     */
    private int $id;

    /**
     * @var string The unique code of the product.
     */
    private string $code;

    /**
     * @var string The description of the product.
     */
    private string $description;

    /**
     * @var float The price of the product.
     */
    private float $price;

    /**
     * Constructor for the Product class.
     *
     * @param int $id The product's ID.
     * @param string $code The product's unique code.
     * @param string $description The product's description.
     * @param float $price The product's price.
     */
    public function __construct(
        int $id, 
        string $code, 
        string $description,
        float $price
    ) {
        $this->id = $id;
        $this->code = $code;
        $this->description = $description;
        $this->price = $price;
    }

    // Getters

    /**
     * Gets the product's ID.
     *
     * @return int The product's ID.
     */
    public function getId(): int {
        return $this->id;
    }

    /**
     * Gets the product's code.
     *
     * @return string The product's code.
     */
    public function getCode(): string {
        return $this->code;
    }

    /**
     * Gets the product's description.
     *
     * @return string The product's description.
     */
    public function getDescription(): string {
        return $this->description;
    }

    /**
     * Gets the product's price.
     *
     * @return float The product's price.
     */
    public function getPrice(): float {
        return $this->price;
    }

    // Setters

    /**
     * Sets the product's ID.
     *
     * @param int $id The product's ID.
     */
    public function setId(int $id): void {
        $this->id = $id;
    }

    /**
     * Sets the product's code.
     *
     * @param string $code The product's code.
     */
    public function setCode(string $code): void {
        $this->code = $code;
    }

    /**
     * Sets the product's description.
     *
     * @param string $description The product's description.
     */
    public function setDescription(string $description): void {
        $this->description = $description;
    }

    /**
     * Sets the product's price.
     *
     * @param float $price The product's price.
     */
    public function setPrice(float $price): void {
        $this->price = $price;
    }

    /**
     * Generates a string representation of the Product object.
     *
     * @return string The string representation of the product.
     */
    public function __toString(): string {
        return sprintf("Product{[id=%d][code=%s][description=%s][price=%.2f]}",
                $this->id, $this->code, $this->description, $this->price);
    }
}
