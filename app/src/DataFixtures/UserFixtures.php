<?php

/**
 * UserFixtures.
 */

namespace App\DataFixtures;

use App\Entity\Enum\UserRole;
use App\Entity\User;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

/**
 * Class UserFixtures.
 */
class UserFixtures extends AbstractBaseFixtures
{
    /**
     * Constructor.
     *
     * @param UserPasswordHasherInterface $hasher User Password Hasher Interface
     */
    public function __construct(private readonly UserPasswordHasherInterface $hasher)
    {
    }

    /**
     * Load data.
     */
    protected function loadData(): void
    {
        $this->createMany(1, 'admin', function ($i) {
            $user = new User();
            $user->setUsername('admin');
            $user->setRoles([UserRole::ROLE_ADMIN->value]);
            $user->setPassword(
                $this->hasher->hashPassword(
                    $user,
                    'admin',
                )
            );

            return $user;
        });
        $this->manager->flush();
    }
}
