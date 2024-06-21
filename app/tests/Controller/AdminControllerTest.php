<?php
/**
 * AdminControllerTest.
 */

namespace App\Tests\Controller;

use App\Entity\Enum\UserRole;
use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Class AdminControllerTest.
 */
class AdminControllerTest extends WebTestCase
{
    /**
     * Set up function.
     */
    public function setUp(): void
    {
        $this->httpClient = static::createClient();
        $this->adminUser = $this->createAdminUser();
    }

    /**
     * Test admin panel route for anonymous user.
     */
    public function testPanelRouteAnonymousUser(): void
    {
        // given
        $expectedStatusCode = 302;

        // when
        $this->httpClient->request('GET', '/admin/panel');
        $statusCode = $this->httpClient->getResponse()->getStatusCode();

        // then
        $this->assertEquals($statusCode, $expectedStatusCode);
    }

    /**
     * Test admin panel route for admin user.
     */
    public function testPanelRouteAdminUser(): void
    {
        // given
        $expectedStatusCode = 200;

        // when
        $this->httpClient->loginUser($this->adminUser);
        $this->httpClient->request('GET', '/admin/panel');
        $statusCode = $this->httpClient->getResponse()->getStatusCode();

        // then
        $this->assertEquals($statusCode, $expectedStatusCode);
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
        $userRepository = static::getContainer()->get(UserRepository::class);
        $userRepository->save($adminUser);

        return $adminUser;
    }
}
