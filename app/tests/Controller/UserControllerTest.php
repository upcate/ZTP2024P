<?php
/**
 * UserControllerTest.
 */

/**
 * This test file is a part of project made as a part of the ZTP course completion.
 *
 * (c) Miłosz Świątek <milosz.swiatek@student.uj.edu.pl>
 */

namespace App\Tests\Controller;

use App\Entity\Enum\UserRole;
use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Class UserControllerTest.
 */
class UserControllerTest extends WebTestCase
{
    /**
     * HttpClient.
     */
    private \Symfony\Bundle\FrameworkBundle\KernelBrowser $httpClient;

    /**
     * AdminUser.
     */
    private User $adminUser;

    /**
     * Set up function.
     */
    public function setUp(): void
    {
        $this->httpClient = static::createClient();
        $this->adminUser = $this->createAdminUser();
    }

    /**
     * Test edit user info route for anonymous user.
     */
    public function testEditRouteAnonymousUser(): void
    {
        // given
        $expectedStatusCode = 302;

        // when
        $this->httpClient->request('GET', 'admin/'.$this->adminUser->getId().'/edit');
        $statusCode = $this->httpClient->getResponse()->getStatusCode();

        // then
        $this->assertEquals($statusCode, $expectedStatusCode);
    }

    /**
     * Test edit user info route for admin user.
     */
    public function testEditRouteAdminUser(): void
    {
        // given
        $expectedStatusCode = 200;

        // when
        $this->httpClient->loginUser($this->adminUser);
        $this->httpClient->request('GET', 'admin/'.$this->adminUser->getId().'/edit');
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
