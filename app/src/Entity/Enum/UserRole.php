<?php

/**
 * UserRole.
 */

namespace App\Entity\Enum;

/**
 * Enum UserRole.
 */
enum UserRole: string
{
    case ROLE_ADMIN = 'ROLE_ADMIN';
    /**
     * Label.
     *
     * @return string Role
     */
    public function label(): string
    {
        return match ($this) {
            UserRole::ROLE_ADMIN => 'ROLE_ADMIN',
        };
    }
}
