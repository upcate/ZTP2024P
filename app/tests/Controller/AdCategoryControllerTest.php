<?php
/**
 * AdCategoryControllerTest.
 */

namespace App\Tests\Controller;

use App\Entity\AdCategory;
use App\Entity\Enum\UserRole;
use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Class AdCategoryControllerTest.
 */
class AdCategoryControllerTest extends WebTestCase
{
    /**
     * Set up function.
     */
    public function setUp(): void
    {
        $this->httpClient = static::createClient();
        $container = static::getContainer();
        $this->entityManager = $container->get('doctrine.orm.entity_manager');
        $this->adminUser = $this->createAdminUser();
    }

    /**
     * Test ad category index route.
     */
    public function testIndexRoute(): void
    {
        // given
        $expectedStatusCode = 200;

        // when
        $this->httpClient->request('GET', '/adCategory');
        $statusCode = $this->httpClient->getResponse()->getStatusCode();

        // then
        $this->assertEquals($expectedStatusCode, $statusCode);
    }

    /**
     * Test show ad category route for anonymous user.
     */
    public function testShowRouteAnonymousUser(): void
    {
        // given
        $expectedStatusCode = 302;

        $adCategory = $this->createAdCategory();

        $this->entityManager->persist($adCategory);
        $this->entityManager->flush();

        // when
        $this->httpClient->request('GET', '/adCategory/'.$adCategory->getId());
        $statusCode = $this->httpClient->getResponse()->getStatusCode();

        // then
        $this->assertEquals($expectedStatusCode, $statusCode);
    }

    /**
     * Test show ad category route for admin user.
     */
    public function testShowRouteAdminUser(): void
    {
        // given
        $expectedStatusCode = 200;

        $adCategory = $this->createAdCategory();

        $this->entityManager->persist($adCategory);
        $this->entityManager->flush();

        // when
        $this->httpClient->loginUser($this->adminUser);
        $this->httpClient->request('GET', '/adCategory/'.$adCategory->getId());
        $statusCode = $this->httpClient->getResponse()->getStatusCode();

        // then
        $this->assertEquals($expectedStatusCode, $statusCode);
    }

    /**
     * Test create ad category route for admin user.
     */
    public function testCreateRouteAdminUser(): void
    {
        // given
        $expectedStatusCode = 200;

        // when
        $this->httpClient->loginUser($this->adminUser);
        $this->httpClient->request('GET', '/adCategory/create');
        $statusCode = $this->httpClient->getResponse()->getStatusCode();

        // then
        $this->assertEquals($expectedStatusCode, $statusCode);
    }

    /**
     * Test ad category create route for anonymous user.
     */
    public function testCreateRouteAnonymousUser(): void
    {
        // given
        $expectedStatusCode = 302;

        // when
        $this->httpClient->request('GET', '/adCategory/create');
        $statusCode = $this->httpClient->getResponse()->getStatusCode();

        // then
        $this->assertEquals($expectedStatusCode, $statusCode);
    }

    /**
     * Test edit ad category route for anonymous user.
     */
    public function testEditRouteAnonymousUser(): void
    {
        // given
        $expectedStatusCode = 302;

        $adCategory = $this->createAdCategory();

        $this->entityManager->persist($adCategory);
        $this->entityManager->flush();

        // when
        $this->httpClient->request('GET', '/adCategory/admin/'.$adCategory->getId().'/edit');
        $statusCode = $this->httpClient->getResponse()->getStatusCode();

        // then
        $this->assertEquals($expectedStatusCode, $statusCode);
    }

    /**
     * Test edit ad category route for admin user.
     */
    public function testEditRouteAdminUser(): void
    {
        // given
        $expectedStatusCode = 200;

        $adCategory = $this->createAdCategory();

        $this->entityManager->persist($adCategory);
        $this->entityManager->flush();

        // when
        $this->httpClient->loginUser($this->adminUser);
        $this->httpClient->request('GET', '/adCategory/admin/'.$adCategory->getId().'/edit');
        $statusCode = $this->httpClient->getResponse()->getStatusCode();

        // then
        $this->assertEquals($expectedStatusCode, $statusCode);
    }

    /**
     * Test ad category delete route for anonymous user.
     */
    public function testDeleteRouteAnonymousUser(): void
    {
        // given
        $expectedStatusCode = 302;

        $adCategory = $this->createAdCategory();

        $this->entityManager->persist($adCategory);
        $this->entityManager->flush();

        // when
        $this->httpClient->request('GET', '/adCategory/'.$adCategory->getId().'/delete');
        $statusCode = $this->httpClient->getResponse()->getStatusCode();

        // then
        $this->assertEquals($expectedStatusCode, $statusCode);
    }

    /**
     * Test ad category delete route for admin user.
     */
    public function testDeleteRouteAdminUser(): void
    {
        // given
        $expectedStatusCode = 200;

        $adCategory = $this->createAdCategory();

        $this->entityManager->persist($adCategory);
        $this->entityManager->flush();

        // when
        $this->httpClient->loginUser($this->adminUser);
        $this->httpClient->request('GET', '/adCategory/'.$adCategory->getId().'/delete');
        $statusCode = $this->httpClient->getResponse()->getStatusCode();

        // then
        $this->assertEquals($expectedStatusCode, $statusCode);
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

    /**
     * Create ad category object.
     *
     * @return AdCategory ad category object
     */
    private function createAdCategory(): AdCategory
    {
        $adCategory = new AdCategory();
        $adCategory->setName('category');

        return $adCategory;
    }
}
