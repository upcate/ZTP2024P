<?php
/**
 * AdControllerTest.
 */

namespace App\Tests\Controller;

use App\Entity\Ad;
use App\Entity\AdCategory;
use App\Entity\Enum\UserRole;
use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Class AdControllerTest.
 */
class AdControllerTest extends WebTestCase
{
    /**
     * Function setUp.
     */
    public function setUp(): void
    {
        $this->httpClient = static::createClient();
        $container = static::getContainer();
        $this->entityManager = $container->get('doctrine.orm.entity_manager');
        $this->adminUser = $this->createAdminUser();
    }

    /**
     * Function test index route for anonymous user.
     */
    public function testIndexRouteAnonymousUser(): void
    {
        // given
        $expectedStatusCode = 200;

        // when
        $this->httpClient->request('GET', '/ad');
        $statusCode = $this->httpClient->getResponse()->getStatusCode();

        // then
        $this->assertEquals($expectedStatusCode, $statusCode);
    }

    /**
     * Function test index route for admin user.
     */
    public function testIndexRouteAdminUser(): void
    {
        // given
        $expectedStatusCode = 200;

        // when
        $this->httpClient->request('GET', '/ad');
        $this->httpClient->loginUser($this->adminUser);
        $statusCode = $this->httpClient->getResponse()->getStatusCode();

        // then
        $this->assertEquals($expectedStatusCode, $statusCode);
    }

    /**
     * Test to accept index route for anonymous user.
     */
    public function testIndexToAcceptRouteAnonymousUser(): void
    {
        // given
        $expectedStatusCode = 302;

        // when
        $this->httpClient->request('GET', '/ad/toAccept');
        $statusCode = $this->httpClient->getResponse()->getStatusCode();

        // then
        $this->assertEquals($expectedStatusCode, $statusCode);
    }

    /**
     * Test to accept index route for admin user.
     */
    public function testIndexToAcceptRouteAdminUser(): void
    {
        // given
        $expectedStatusCode = 200;

        // when
        $this->httpClient->loginUser($this->adminUser);
        $this->httpClient->request('GET', '/ad/toAccept');
        $statusCode = $this->httpClient->getResponse()->getStatusCode();

        // then
        $this->assertEquals($expectedStatusCode, $statusCode);
    }

    /**
     * Test accept route anonymous user.
     */
    public function testAcceptRouteAnonymousUser(): void
    {
        // given
        $expectedStatusCode = 302;
        $adCategory = $this->createAdCategory();
        $ad = $this->createAd($adCategory);

        $this->entityManager->persist($ad);
        $this->entityManager->persist($adCategory);
        $this->entityManager->flush();

        // when
        $this->httpClient->request('GET', '/ad/'.$ad->getId().'/accept');
        $statusCode = $this->httpClient->getResponse()->getStatusCode();

        // then
        $this->assertEquals($expectedStatusCode, $statusCode);
    }

    /**
     * Test accept route admin user.
     */
    public function testAcceptRouteAdminUser(): void
    {
        // given
        $expectedStatusCode = 200;
        $adCategory = $this->createAdCategory();
        $ad = $this->createAd($adCategory);

        $this->entityManager->persist($ad);
        $this->entityManager->persist($adCategory);
        $this->entityManager->flush();

        // when
        $this->httpClient->loginUser($this->adminUser);
        $this->httpClient->request('GET', '/ad/'.$ad->getId().'/accept');
        $statusCode = $this->httpClient->getResponse()->getStatusCode();

        // then
        $this->assertEquals($expectedStatusCode, $statusCode);
    }

    /**
     * Test show ad route for ad to accept for anonymous user.
     */
    public function testShowRouteAnonymousUserAdToAccept(): void
    {
        // given
        $expectedStatusCode = 302;

        $adCategory = $this->createAdCategory();
        $ad = $this->createAd($adCategory);

        $this->entityManager->persist($adCategory);
        $this->entityManager->persist($ad);
        $this->entityManager->flush();

        // when
        $this->httpClient->request('GET', '/ad/'.$ad->getId());
        $statusCode = $this->httpClient->getResponse()->getStatusCode();

        // then
        $this->assertEquals($expectedStatusCode, $statusCode);
    }

    /**
     * Test show ad route for ad to accept for admin user.
     */
    public function testShowRouteAdminUserAdToAccept(): void
    {
        // given
        $expectedStatusCode = 200;

        $adCategory = $this->createAdCategory();
        $ad = $this->createAd($adCategory);

        $this->entityManager->persist($adCategory);
        $this->entityManager->persist($ad);
        $this->entityManager->flush();

        // when
        $this->httpClient->loginUser($this->adminUser);
        $this->httpClient->request('GET', '/ad/'.$ad->getId());
        $statusCode = $this->httpClient->getResponse()->getStatusCode();

        // then
        $this->assertEquals($expectedStatusCode, $statusCode);
    }

    /**
     * test show route for accepted ad.
     */
    public function testShowRouteAccepted(): void
    {
        // given
        $expectedStatusCode = 200;

        $adCategory = $this->createAdCategory();
        $ad = $this->createAd($adCategory);
        $ad->setIsVisible(1);

        $this->entityManager->persist($adCategory);
        $this->entityManager->persist($ad);
        $this->entityManager->flush();

        // when
        $this->httpClient->request('GET', '/ad/'.$ad->getId());
        $statusCode = $this->httpClient->getResponse()->getStatusCode();

        // then
        $this->assertEquals($expectedStatusCode, $statusCode);
    }

    /**
     * Test create route.
     */
    public function testCreateRoute(): void
    {
        // given
        $expectedStatusCode = 200;

        // when
        $this->httpClient->request('GET', '/ad/create');
        $statusCode = $this->httpClient->getResponse()->getStatusCode();

        // then
        $this->assertEquals($expectedStatusCode, $statusCode);
    }

    /**
     * Test edit route for anonymous user.
     */
    public function testEditRouteAnonymousUser(): void
    {
        // given
        $expectedStatusCode = 302;

        $adCategory = $this->createAdCategory();
        $ad = $this->createAd($adCategory);

        $this->entityManager->persist($adCategory);
        $this->entityManager->persist($ad);
        $this->entityManager->flush();

        // when
        $this->httpClient->request('GET', '/ad/'.$ad->getId().'/edit');
        $statusCode = $this->httpClient->getResponse()->getStatusCode();

        // then
        $this->assertEquals($expectedStatusCode, $statusCode);
    }

    /**
     * Test edit route for admin user.
     */
    public function testEditRouteAdminUser(): void
    {
        // given
        $expectedStatusCode = 200;

        $adCategory = $this->createAdCategory();
        $ad = $this->createAd($adCategory);

        $this->entityManager->persist($adCategory);
        $this->entityManager->persist($ad);
        $this->entityManager->flush();

        // when
        $this->httpClient->loginUser($this->adminUser);
        $this->httpClient->request('GET', '/ad/'.$ad->getId().'/edit');
        $statusCode = $this->httpClient->getResponse()->getStatusCode();

        // then
        $this->assertEquals($expectedStatusCode, $statusCode);
    }

    /**
     * Test delete route for anonymous user.
     */
    public function testDeleteRouteAnonymousUser(): void
    {
        // given
        $expectedStatusCode = 302;

        $adCategory = $this->createAdCategory();
        $ad = $this->createAd($adCategory);

        $this->entityManager->persist($adCategory);
        $this->entityManager->persist($ad);
        $this->entityManager->flush();

        // when
        $this->httpClient->request('GET', '/ad/'.$ad->getId().'/delete');
        $statusCode = $this->httpClient->getResponse()->getStatusCode();

        // then
        $this->assertEquals($expectedStatusCode, $statusCode);
    }

    /**
     * Test delete route for admin user.
     */
    public function testDeleteRouteAdminUser(): void
    {
        // given
        $expectedStatusCode = 200;

        $adCategory = $this->createAdCategory();
        $ad = $this->createAd($adCategory);

        $this->entityManager->persist($adCategory);
        $this->entityManager->persist($ad);
        $this->entityManager->flush();

        // when
        $this->httpClient->loginUser($this->adminUser);
        $this->httpClient->request('GET', '/ad/'.$ad->getId().'/delete');
        $statusCode = $this->httpClient->getResponse()->getStatusCode();

        // then
        $this->assertEquals($expectedStatusCode, $statusCode);
    }

    /**
     * Test delete route for ad to accept for anonymous user.
     */
    public function testDeleteToAcceptRouteAnonymousUser(): void
    {
        // given
        $expectedStatusCode = 302;

        $adCategory = $this->createAdCategory();
        $ad = $this->createAd($adCategory);

        $this->entityManager->persist($adCategory);
        $this->entityManager->persist($ad);
        $this->entityManager->flush();

        // when
        $this->httpClient->request('GET', '/ad/'.$ad->getId().'/delete');
        $statusCode = $this->httpClient->getResponse()->getStatusCode();

        // then
        $this->assertEquals($expectedStatusCode, $statusCode);
    }

    /**
     * Test delete route for ad to accept for admin user.
     */
    public function testDeleteToAcceptRouteAdminUser(): void
    {
        // given
        $expectedStatusCode = 200;

        $adCategory = $this->createAdCategory();
        $ad = $this->createAd($adCategory);

        $this->entityManager->persist($adCategory);
        $this->entityManager->persist($ad);
        $this->entityManager->flush();

        // when
        $this->httpClient->loginUser($this->adminUser);
        $this->httpClient->request('GET', '/ad/'.$ad->getId().'/delete');
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

    /**
     * Create Ad function.
     *
     * @param AdCategory $adCategory ad category object
     *
     * @return Ad Ad object
     */
    private function createAd(AdCategory $adCategory): Ad
    {
        $date = new \DateTimeImmutable();
        $ad = new Ad();
        $ad->setUsername('username');
        $ad->setEmail('email@email.com');
        $ad->setPhone(123456789);
        $ad->setText('text');
        $ad->setIsVisible(0);
        $ad->setTitle('title');
        $ad->setAdCategory($adCategory);
        $ad->setCreatedAt($date);

        return $ad;
    }
}
