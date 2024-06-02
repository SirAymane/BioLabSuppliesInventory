<?php
namespace SirAymane\ecommerce\model;

use DateTime;

/**
 * User model class representing a user in the ecommerce application.
 */
class User {
    
    /**
     * @var int The unique identifier for the user.
     */
    private int $id;

    /**
     * @var string|null The username of the user.
     */
    private ?string $username;

    /**
     * @var string|null The password of the user.
     */
    private ?string $password;

    /**
     * @var string|null The role of the user (e.g., admin, registered).
     */
    private ?string $role;

    /**
     * @var string|null The email address of the user.
     */
    private ?string $email;

    /**
     * @var DateTime|null The date of birth of the user.
     */
    private ?DateTime $dob;

    /**
     * Constructor for the User class.
     *
     * @param int $id The user's ID.
     * @param string|null $username The user's username.
     * @param string|null $password The user's password.
     * @param string|null $role The user's role.
     * @param string|null $email The user's email address.
     * @param DateTime|null $dob The user's date of birth.
     */
    public function __construct(
        int $id = 0, 
        ?string $username = null, 
        ?string $password = null,
        ?string $role = null,
        ?string $email = null,
        ?DateTime $dob = null
    ) {
        $this->id = $id;
        $this->username = $username;
        $this->password = $password ? $this->hashPassword($password) : null;
        $this->role = $role;
        $this->email = $email;
        $this->dob = $dob;
    }

    public function getId(): int {
        return $this->id;
    }

    public function getUsername(): ?string {
        return $this->username;
    }

    public function getPassword(): ?string {
        return $this->password;
    }

    public function getRole(): ?string {
        return $this->role;
    }

    public function getEmail(): ?string {
        return $this->email;
    }

    public function getDob(): ?DateTime {
        return $this->dob;
    }

    public function setId(int $id): void {
        $this->id = $id;
    }

    public function setUsername(?string $username): void {
        $this->username = $username;
    }

    public function setPassword(?string $password): void {
        $this->password = $password;
    }

    public function setrole(?string $role): void {
        $this->role = $role;
    }

    public function setemail(?string $email): void {
        $this->email = $email;
    }

    public function setdob(?DateTime $dob): void {
        $this->dob = $dob;
    }


    /**
     * Sets the password without hashing it.
     * This method should be used only when you are sure that the password is already hashed,
     * for example when loading user data from the database.
     *
     * @param string $password The hashed password.
     */
    public function setRawPassword(string $password): void {
        $this->password = $password;
    }

    /**
     * Hashes the password using a secure hashing algorithm.
     *
     * @param string $password The plain text password to hash.
     * @return string The hashed password.
     */
    private function hashPassword(string $password): string {
        return password_hash($password, PASSWORD_DEFAULT);
    }

    /**
     * Generates a string representation of the User object.
     *
     * @return string The string representation of the user.
     */
    public function __toString(): string {
        return sprintf("User{[id=%d][username=%s][password=%s][role=%s][email=%s][dob=%s]}",
                $this->id, $this->username, $this->password, $this->role, $this->email, 
                $this->dob ? $this->dob->format('Y-m-d') : null);
    }

}