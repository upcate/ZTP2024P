<?php
/**
 * AdServiceInterfaceTest.
 */

/**
 * This test file is a part of project made as a part of the ZTP course completion.
 *
 * (c) Miłosz Świątek <milosz.swiatek@student.uj.edu.pl>
 */

namespace App\Tests\Service;

use App\Entity\Ad;
use App\Entity\AdCategory;
use App\Repository\AdRepository;
use App\Service\AdCategoryService;
use App\Service\AdService;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * Class AdServiceInterfaceTest.
 */
class AdServiceTest extends KernelTestCase
{
    /**
     * EntityManager.
     */
    private mixed $entityManager;

    /**
     * AdCategoryService.
     */
    private AdCategoryService $adCategoryService;

    /**
     * AdService.
     */
    private AdService $adService;

    /**
     * AdRepository.
     */
    private AdRepository $adRepository;

    /**
     * Set up function.
     */
    public function setUp(): void
    {
        $container = static::getContainer();
        $this->entityManager = $container->get('doctrine.orm.entity_manager');
        $this->adCategoryService = $container->get(AdCategoryService::class);
        $this->adService = $container->get(AdService::class);
        $this->adRepository = $container->get(AdRepository::class);
    }

    /**
     * Test get paginated list for last page.
     */
    public function testGetPaginatedListLastPage(): void
    {
        // given
        $page = 3;
        $numberOfAds = 21;
        $expectedNumberOfAdsOnPage = 1;
        $filters = [];

        $adCategory = $this->createAdCategory();
        $this->adCategoryService->save($adCategory);

        $counter = 1;
        while ($counter <= $numberOfAds) {
            $ad = $this->createAd($adCategory);
            $ad->setTitle('Ad No. '.$counter);
            $this->adService->save($ad);

            ++$counter;
        }

        // when
        $result = $this->adService->getPaginatedList($page, $filters);

        // then
        $this->assertEquals($expectedNumberOfAdsOnPage, $result->count());
    }

    /**
     * Test get paginated list for middle page.
     */
    public function testGetPaginatedListMiddlePage(): void
    {
        // given
        $page = 2;
        $numberOfAds = 21;
        $expectedNumberOfAdsOnPage = 10;
        $filters = [];

        $adCategory = $this->createAdCategory();
        $this->adCategoryService->save($adCategory);

        $counter = 1;
        while ($counter <= $numberOfAds) {
            $ad = $this->createAd($adCategory);
            $ad->setTitle('Ad No. '.$counter);
            $this->adService->save($ad);

            ++$counter;
        }

        // when
        $result = $this->adService->getPaginatedList($page, $filters);

        // then
        $this->assertEquals($expectedNumberOfAdsOnPage, $result->count());
    }

    /**
     * Test get paginated list for first page.
     */
    public function testGetPaginatedListFirstPage(): void
    {
        // given
        $page = 1;
        $numberOfAds = 21;
        $expectedNumberOfAdsOnPage = 10;
        $filters = [];

        $adCategory = $this->createAdCategory();
        $this->adCategoryService->save($adCategory);

        $counter = 1;
        while ($counter <= $numberOfAds) {
            $ad = $this->createAd($adCategory);
            $ad->setTitle('Ad No. '.$counter);
            $this->adService->save($ad);

            ++$counter;
        }

        // when
        $result = $this->adService->getPaginatedList($page, $filters);

        // then
        $this->assertEquals($expectedNumberOfAdsOnPage, $result->count());
    }

    /**
     * Test get paginated list for ads to accept for last page.
     */
    public function testGetPaginatedAcceptListLastPage(): void
    {
        // given
        $page = 3;
        $numberOfAds = 21;
        $expectedNumberOfAdsOnPage = 1;
        $filters = [];

        $adCategory = $this->createAdCategory();
        $this->adCategoryService->save($adCategory);

        $counter = 1;
        while ($counter <= $numberOfAds) {
            $ad = $this->createAd($adCategory);
            $ad->setTitle('Ad No. '.$counter);
            $ad->setIsVisible(0);
            $this->adService->save($ad);

            ++$counter;
        }

        // when
        $result = $this->adService->getPaginatedAcceptList($page, $filters);

        // then
        $this->assertEquals($expectedNumberOfAdsOnPage, $result->count());
    }

    /**
     * Test get paginated list of ads to accept for middle page.
     */
    public function testGetPaginatedAcceptListMiddlePage(): void
    {
        // given
        $page = 2;
        $numberOfAds = 21;
        $expectedNumberOfAdsOnPage = 10;
        $filters = [];

        $adCategory = $this->createAdCategory();
        $this->adCategoryService->save($adCategory);

        $counter = 1;
        while ($counter <= $numberOfAds) {
            $ad = $this->createAd($adCategory);
            $ad->setTitle('Ad No. '.$counter);
            $ad->setIsVisible(0);
            $this->adService->save($ad);

            ++$counter;
        }

        // when
        $result = $this->adService->getPaginatedAcceptList($page, $filters);

        // then
        $this->assertEquals($expectedNumberOfAdsOnPage, $result->count());
    }

    /**
     * Test get paginated list of ads to accept for first page.
     */
    public function testGetPaginatedAcceptListFirstPage(): void
    {
        // given
        $page = 1;
        $numberOfAds = 21;
        $expectedNumberOfAdsOnPage = 10;
        $filters = [];

        $adCategory = $this->createAdCategory();
        $this->adCategoryService->save($adCategory);

        $counter = 1;
        while ($counter <= $numberOfAds) {
            $ad = $this->createAd($adCategory);
            $ad->setTitle('Ad No. '.$counter);
            $ad->setIsVisible(0);
            $this->adService->save($ad);

            ++$counter;
        }

        // when
        $result = $this->adService->getPaginatedAcceptList($page, $filters);

        // then
        $this->assertEquals($expectedNumberOfAdsOnPage, $result->count());
    }

    /**
     * Test save ad.
     */
    public function testSave(): void
    {
        // given
        $adCategory = $this->createAdCategory();
        $ad = $this->createAd($adCategory);

        $this->entityManager->persist($adCategory);
        $this->entityManager->flush();

        // when
        $this->adService->save($ad);
        $adId = $ad->getId();

        $savedAd = $this->entityManager->CreateQueryBuilder()
            ->select('ads')
            ->from(Ad::class, 'ads')
            ->where("ads.id = {$adId}")
            ->getQuery()
            ->getSingleResult();

        // then
        $this->assertEquals($ad, $savedAd);
    }

    /**
     * Test delete ad.
     */
    public function testDelete(): void
    {
        // given
        $adCategory = $this->createAdCategory();
        $ad = $this->createAd($adCategory);

        $this->entityManager->persist($adCategory);
        $this->entityManager->flush();

        // when
        $this->adService->save($ad);
        $adId = $ad->getId();
        $this->adService->delete($ad);

        $savedAd = $this->entityManager->createQueryBuilder()
            ->select('ads')
            ->from(Ad::class, 'ads')
            ->where("ads.id = {$adId}")
            ->getQuery()
            ->getOneOrNullResult();

        // then
        $this->assertNull($savedAd);
    }

    /**
     * Test save ad on creation by admin.
     */
    public function testSaveOnCreateAdm(): void
    {
        // given
        $adCategory = $this->createAdCategory();
        $ad = $this->createAd($adCategory);

        $this->entityManager->persist($adCategory);
        $this->entityManager->flush();

        // when
        $this->adService->saveOnCreateAdm($ad);
        $adId = $ad->getId();
        $expectedAdIsVisible = 1;

        $savedAdIsVisible = $this->entityManager->CreateQueryBuilder()
            ->select('ads.isVisible')
            ->from(Ad::class, 'ads')
            ->where("ads.id = {$adId}")
            ->getQuery()
            ->getSingleScalarResult();

        // then
        $this->assertEquals($expectedAdIsVisible, $savedAdIsVisible);
    }

    /**
     * Test save ad on creation by user.
     */
    public function testSaveOnCreateUs(): void
    {
        // given
        $adCategory = $this->createAdCategory();
        $ad = $this->createAd($adCategory);

        $this->entityManager->persist($adCategory);
        $this->entityManager->flush();

        // when
        $this->adService->saveOnCreateUs($ad);
        $adId = $ad->getId();
        $expectedAdIsVisible = 0;

        $savedAdIsVisible = $this->entityManager->CreateQueryBuilder()
            ->select('ads.isVisible')
            ->from(Ad::class, 'ads')
            ->where("ads.id = {$adId}")
            ->getQuery()
            ->getSingleScalarResult();

        // then
        $this->assertEquals($expectedAdIsVisible, $savedAdIsVisible);
    }

    /**
     * Test make ad visible.
     */
    public function testMakeVisible(): void
    {
        // given
        $adCategory = $this->createAdCategory();
        $ad = $this->createAd($adCategory);
        $ad->setIsVisible(0);

        $this->entityManager->persist($adCategory);
        $this->entityManager->flush();

        // when
        $this->adService->save($ad);
        $adId = $ad->getId();
        $this->adService->makeVisible($ad);
        $expectedAdIsVisible = 1;

        $savedAdIsVisible = $this->entityManager->CreateQueryBuilder()
            ->select('ads.isVisible')
            ->from(Ad::class, 'ads')
            ->where("ads.id = {$adId}")
            ->getQuery()
            ->getSingleScalarResult();

        // then
        $this->assertEquals($expectedAdIsVisible, $savedAdIsVisible);
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
        $ad->setIsVisible(1);
        $ad->setTitle('title');
        $ad->setAdCategory($adCategory);
        $ad->setCreatedAt($date);
        $ad->setUpdatedAt($date);

        return $ad;
    }
}
