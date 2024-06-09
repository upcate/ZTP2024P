<?php

/**
 * User.
 */

namespace App\Entity;

use App\Entity\Enum\UserRole;
use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class User.
 */
#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: 'users')]
#[ORM\UniqueConstraint(name: 'username_idx', columns: ['username'])]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    /**
     * Primary Key.
     *
     * @var int|null Id
     */
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    /**
     * Username.
     *
     * @var string|null Username
     */
    #[ORM\Column(type: 'string', length: 20, unique: true)]
    #[Assert\NotBlank]
    #[Assert\Length(min: 3, max: 20)]
    private ?string $username = null;

    /**
     * Roles.
     *
     * @var array Roles
     */
    #[ORM\Column(type: 'json')]
    private array $roles = [];

    /**
     * Password.
     *
     * @var string|null Password
     */
    #[ORM\Column(type: 'string')]
    #[Assert\NotBlank]
    private ?string $password = null;

    /**
     * Getter for id.
     *
     * @return int|null Id
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Get username.
     *
     * @return string Username
     */
    public function getUsername(): string
    {
        return (string) $this->username;
    }

    /**
     * Setter for username.
     *
     * @param string $username Username
     */
    public function setUsername(string $username): void
    {
        $this->username = $username;
    }

    /**
     * Get user identifier.
     *
     * @return string Identifier
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->username;
    }

    /**
     * Getter for roles.
     *
     * @return array|string[] Roles
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = UserRole::ROLE_ADMIN->value;

        return array_unique($roles);
    }

    /**
     * Setter for roles.
     *
     * @param array $roles Roles
     */
    public function setRoles(array $roles): void
    {
        $this->roles = $roles;
    }

    /**
     * Getter for password.
     *
     * @return string Password
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * Setter for password.
     *
     * @param string $password Password
     */
    public function setPassword(string $password): void
    {
        $this->password = $password;
    }

    /**
     * Get salt.
     *
     * @return string|null Salt
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * Erase Credentials.
     *
     * @see UserInterface User interface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }
}
