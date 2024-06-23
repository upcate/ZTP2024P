<?php
/**
 * UserServiceTest.
 */

/**
 * This test file is a part of project made as a part of the ZTP course completion.
 *
 * (c) Miłosz Świątek <milosz.swiatek@student.uj.edu.pl>
 */

namespace App\Tests\Service;

use App\Entity\Enum\UserRole;
use App\Entity\User;
use App\Repository\UserRepository;
use App\Service\UserService;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * Class UserServiceTest.
 */
class UserServiceTest extends KernelTestCase
{
    /**
     * Set up function.
     */
    public function setUp(): void
    {
        $container = static::getContainer();
        $this->userService = $container->get(UserService::class);
        $this->entityManager = $container->get('doctrine.orm.entity_manager');
    }

    /**
     * Test save.
     */
    public function testSave(): void
    {
        // given
        $adminUser = $this->createAdminUser();

        // when
        $this->userService->save($adminUser);
        $adminUserId = $adminUser->getId();

        $savedAdminUser = $this->entityManager->createQueryBuilder()
            ->select('users')
            ->from(User::class, 'users')
            ->where("users.id = {$adminUserId}")
            ->getQuery()
            ->getSingleResult();

        // then
        $this->assertEquals($adminUser, $savedAdminUser);
    }

    /**
     * Private function create admin user.
     *
     * @return User user object
     */
    private function createAdminUser(): User
    {
        $hasher = static::getContainer()->get('security.password_hasher');

        $adminUser = new User();
        $adminUser->setUsername('admin');
        $adminUser->setRoles([UserRole::ROLE_ADMIN->value]);
        $adminUser->setPassword(
            $hasher->hashPassword(
                $adminUser,
                'admin'
            )
        );

        return $adminUser;
    }
}
