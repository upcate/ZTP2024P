<?php
/**
 * AdCategoryServiceTest.
 */

/**
 * This test file is a part of project made as a part of the ZTP course completion.
 *
 * (c) Miłosz Świątek <milosz.swiatek@student.uj.edu.pl>
 */

namespace App\Tests\Service;

use App\Entity\Ad;
use App\Entity\AdCategory;
use App\Repository\AdCategoryRepository;
use App\Repository\AdRepository;
use App\Service\AdCategoryService;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * Class AdCategoryServiceTest.
 */
class AdCategoryServiceTest extends KernelTestCase
{
    /**
     * Set up function.
     */
    public function setUp(): void
    {
        $container = static::getContainer();
        $this->entityManager = $container->get('doctrine.orm.entity_manager');
        $this->adCategoryService = $container->get(AdCategoryService::class);
    }

    /**
     * Test get paginated list of categories for last page.
     */
    public function testGetPaginatedListLastPage(): void
    {
        // given
        $page = 3;
        $numberOfCategories = 21;
        $expectedNumberOfCategoriesOnPage = 1;

        $counter = 1;
        while ($counter <= $numberOfCategories) {
            $adCategory = $this->createAdCategory();
            $adCategory->setName('Category No. '.$counter);
            $this->adCategoryService->save($adCategory);

            ++$counter;
        }

        // when
        $result = $this->adCategoryService->getPaginatedList($page);

        // then
        $this->assertEquals($expectedNumberOfCategoriesOnPage, $result->count());
    }

    /**
     * Test get paginated list of categories for middle page.
     */
    public function testGetPaginatedListMiddlePage(): void
    {
        // given
        $page = 2;
        $numberOfCategories = 21;
        $expectedNumberOfCategoriesOnPage = 10;

        $counter = 1;
        while ($counter <= $numberOfCategories) {
            $adCategory = $this->createAdCategory();
            $adCategory->setName('Category No. '.$counter);
            $this->adCategoryService->save($adCategory);

            ++$counter;
        }

        // when
        $result = $this->adCategoryService->getPaginatedList($page);

        // then
        $this->assertEquals($expectedNumberOfCategoriesOnPage, $result->count());
    }

    /**
     * Test get paginated list of categories for middle page.
     */
    public function testGetPaginatedListFirstPage(): void
    {
        // given
        $page = 1;
        $numberOfCategories = 21;
        $expectedNumberOfCategoriesOnPage = 10;

        $counter = 1;
        while ($counter <= $numberOfCategories) {
            $adCategory = $this->createAdCategory();
            $adCategory->setName('Category No. '.$counter);
            $this->adCategoryService->save($adCategory);

            ++$counter;
        }

        // when
        $result = $this->adCategoryService->getPaginatedList($page);

        // then
        $this->assertEquals($expectedNumberOfCategoriesOnPage, $result->count());
    }

    /**
     * Test save.
     */
    public function testSave(): void
    {
        // given
        $adCategory = $this->createAdCategory();

        // when
        $this->adCategoryService->save($adCategory);
        $adCategoryId = $adCategory->getId();

        $savedAdCategory = $this->entityManager->createQueryBuilder()
            ->select('adCategories')
            ->from(AdCategory::class, 'adCategories')
            ->where("adCategories.id = {$adCategoryId}")
            ->getQuery()
            ->getSingleResult();

        // then
        $this->assertEquals($adCategory, $savedAdCategory);
    }

    /**
     * Test delete.
     */
    public function testDelete(): void
    {
        // given
        $adCategory = $this->createAdCategory();

        // when
        $this->adCategoryService->save($adCategory);
        $adCategoryId = $adCategory->getId();
        $this->adCategoryService->delete($adCategory);

        $savedAdCategory = $this->entityManager->createQueryBuilder()
            ->select('adCategories')
            ->from(AdCategory::class, 'adCategories')
            ->where("adCategories.id = {$adCategoryId}")
            ->getQuery()
            ->getOneOrNullResult();

        // then
        $this->assertNull($savedAdCategory);
    }

    /**
     * Test if canBeDeleted function return true if count is equal to 0.
     */
    public function testCanBeDeletedReturnsTrueWhenCountIsZero(): void
    {
        // given
        $adCategory = $this->createAdCategory();
        $this->adCategoryService->save($adCategory);

        // when
        $value = $this->adCategoryService->canBeDeleted($adCategory);

        // then
        $this->assertTrue($value);
    }

    /**
     * Test if canBeDeleted function returns false if count is greater than 0.
     */
    public function testCanBeDeletedReturnsFalseWhenCountIsGreaterThanZero(): void
    {
        // given
        $adCategory = $this->createAdCategory();
        $this->adCategoryService->save($adCategory);
        $ad = $this->createAd($adCategory);
        $this->entityManager->persist($ad);
        $this->entityManager->flush();

        // when
        $value = $this->adCategoryService->canBeDeleted($adCategory);

        // then
        $this->assertFalse($value);
    }

    /**
     * Test if canBeDeleted returns false if NoResultException is thrown.
     */
    public function testCanBeDeletedReturnsFalseWhenNoResultExceptionIsThrown(): void
    {
        // given
        $adCategory = $this->createMock(AdCategory::class);
        $adRepository = $this->createMock(AdRepository::class);
        $adCategoryRepository = $this->createMock(AdCategoryRepository::class);
        $adRepository->method('countByCategory')->willThrowException(new NoResultException());
        $paginator = $this->createMock(PaginatorInterface::class);
        $adCategoryService = new AdCategoryService($adCategoryRepository, $paginator, $adRepository);

        // when
        $value = $adCategoryService->canBeDeleted($adCategory);

        // then
        $this->assertFalse($value);
    }

    /**
     * Test if canBeDeleted returns false if NoResultExcpetion is thrown.
     */
    public function testCanBeDeletedReturnsFalseWhenNoUniqueResultExceptionIsThrown(): void
    {
        // given
        $adCategory = $this->createMock(AdCategory::class);
        $adRepository = $this->createMock(AdRepository::class);
        $adCategoryRepository = $this->createMock(AdCategoryRepository::class);
        $adRepository->method('countByCategory')->willThrowException(new NonUniqueResultException());
        $paginator = $this->createMock(PaginatorInterface::class);
        $adCategoryService = new AdCategoryService($adCategoryRepository, $paginator, $adRepository);

        // when
        $value = $adCategoryService->canBeDeleted($adCategory);

        // then
        $this->assertFalse($value);
    }

    /**
     * Test find ad category by id.
     */
    public function testFindOneById(): void
    {
        // given
        $adCategory = $this->createAdCategory();
        $this->adCategoryService->save($adCategory);
        $adCategoryId = $adCategory->getId();

        // when
        $adCategoryResult = $this->adCategoryService->findOneById($adCategoryId);

        // then
        $this->assertEquals($adCategory, $adCategoryResult);
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
