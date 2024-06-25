<?php

/**
 * AdCategoryService.
 */

namespace App\Service;

use App\Entity\AdCategory;
use App\Repository\AdCategoryRepository;
use App\Repository\AdRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;

/**
 * Class AdCategoryService.
 */
class AdCategoryService implements AdCategoryServiceInterface
{
    /**
     * Constructor.
     *
     * @param AdCategoryRepository $adCategoryRepository Ad category repository
     * @param PaginatorInterface   $paginator            Paginator interface
     * @param AdRepository         $adRepository         Ad repository
     */
    public function __construct(private readonly AdCategoryRepository $adCategoryRepository, private readonly PaginatorInterface $paginator, private readonly AdRepository $adRepository)
    {
    }// end __construct()

    /**
     * Get paginated list.
     *
     * @param int $page Number of page
     *
     * @return PaginationInterface Pagination Interface
     */
    public function getPaginatedList(int $page): PaginationInterface
    {
        return $this->paginator->paginate(
            $this->adCategoryRepository->queryAll(),
            $page,
            AdCategoryRepository::PAGINATOR_ITEMS_PER_PAGE
        );
    }// end getPaginatedList()

    /**
     * Save.
     *
     * @param AdCategory $adCategory AdCategory Entity
     */
    public function save(AdCategory $adCategory): void
    {
        if (null === $adCategory->getId()) {
            $adCategory->setCreatedAt(new \DateTimeImmutable());
        }

        $adCategory->setUpdatedAt(new \DateTimeImmutable());

        $this->adCategoryRepository->save($adCategory);
    }// end save()

    /**
     * Delete.
     *
     * @param AdCategory $adCategory AdCategory Entity
     */
    public function delete(AdCategory $adCategory): void
    {
        $this->adCategoryRepository->delete($adCategory);
    }// end delete()

    /**
     * Check can be deleted.
     *
     * @param AdCategory $adCategory AdCategory Entity
     *
     * @return bool Bool return
     */
    public function canBeDeleted(AdCategory $adCategory): bool
    {
        try {
            $result = $this->adRepository->countByCategory($adCategory);

            return $result <= 0;
        } catch (NoResultException|NonUniqueResultException) {
            return false;
        }
    }// end canBeDeleted()

    /**
     * Find one by id.
     *
     * @param int $id Id
     *
     * @return AdCategory|null AdCategory Entity
     */
    public function findOneById(int $id): ?AdCategory
    {
        return $this->adCategoryRepository->findOneById($id);
    }// end findOneById()
}// end class
