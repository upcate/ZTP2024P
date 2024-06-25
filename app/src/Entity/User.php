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
    }// end getId()

    /**
     * Get username.
     *
     * @return string Username
     */
    public function getUsername(): string
    {
        return (string) $this->username;
    }// end getUsername()

    /**
     * Setter for username.
     *
     * @param string $username Username
     */
    public function setUsername(string $username): void
    {
        $this->username = $username;
    }// end setUsername()

    /**
     * Get user identifier.
     *
     * @return string Identifier
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->username;
    }// end getUserIdentifier()

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
    }// end getRoles()

    /**
     * Setter for roles.
     *
     * @param array $roles Roles
     */
    public function setRoles(array $roles): void
    {
        $this->roles = $roles;
    }// end setRoles()

    /**
     * Getter for password.
     *
     * @return string Password
     */
    public function getPassword(): string
    {
        return $this->password;
    }// end getPassword()

    /**
     * Setter for password.
     *
     * @param string $password Password
     */
    public function setPassword(string $password): void
    {
        $this->password = $password;
    }// end setPassword()

    /**
     * Get salt.
     *
     * @return string|null Salt
     */
    public function getSalt(): ?string
    {
        return null;
    }// end getSalt()

    /**
     * Erase Credentials.
     *
     * @see UserInterface User interface
     *
     * @return void might add void as a native return
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }// end eraseCredentials()
}// end class
