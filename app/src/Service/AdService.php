<?php

/**
 * AdService.
 */

namespace App\Service;

use App\Entity\Ad;
use App\Repository\AdRepository;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;

/**
 * Class AdService.
 */
class AdService implements AdServiceInterface
{
    /**
     * Constructor.
     *
     * @param AdRepository               $adRepository      Ad repository
     * @param PaginatorInterface         $paginator         Paginator interface
     * @param AdCategoryServiceInterface $adCategoryService Ad category service interface
     */
    public function __construct(private readonly AdRepository $adRepository, private readonly PaginatorInterface $paginator, private readonly AdCategoryServiceInterface $adCategoryService)
    {
    }// end __construct()

    /**
     * Get paginated list.
     *
     * @param int   $page    Number of page
     * @param array $filters Array of filters
     *
     * @return PaginationInterface Pagination interface
     */
    public function getPaginatedList(int $page, array $filters = []): PaginationInterface
    {
        $filters = $this->prepareFilters($filters);

        return $this->paginator->paginate(
            $this->adRepository->queryAll($filters),
            $page,
            AdRepository::PAGINATOR_ITEMS_PER_PAGE
        );
    }// end getPaginatedList()

    /**
     * Get paginated list with ads to accept.
     *
     * @param int $page Number of page
     *
     * @return PaginationInterface Pagination interface
     */
    public function getPaginatedAcceptList(int $page): PaginationInterface
    {
        return $this->paginator->paginate(
            $this->adRepository->queryToAccept(),
            $page,
            AdRepository::PAGINATOR_ITEMS_PER_PAGE
        );
    }// end getPaginatedAcceptList()

    /**
     * Save.
     *
     * @param Ad $ad Ad Entity
     */
    public function save(Ad $ad): void
    {
        $this->adRepository->save($ad);
    }// end save()

    /**
     * Save on creation by admin.
     *
     * @param Ad $ad Ad Entity
     */
    public function saveOnCreateAdm(Ad $ad): void
    {
        $ad->setIsVisible(1);
        $this->adRepository->save($ad);
    }// end saveOnCreateAdm()

    /**
     * Save on creation by user.
     *
     * @param Ad $ad Ad Entity
     */
    public function saveOnCreateUs(Ad $ad): void
    {
        $ad->setIsVisible(0);
        $this->adRepository->save($ad);
    }// end saveOnCreateUs()

    /**
     * Delete.
     *
     * @param Ad $ad Ad Entity
     */
    public function delete(Ad $ad): void
    {
        $this->adRepository->delete($ad);
    }// end delete()

    /**
     * Make ad visible.
     *
     * @param Ad $ad Ad Entity
     */
    public function makeVisible(Ad $ad): void
    {
        $ad->setIsVisible(1);
        $this->adRepository->save($ad);
    }// end makeVisible()

    /**
     * Prepare filters.
     *
     * @param array $filters Array of filters
     *
     * @return array Array of filters
     */
    private function prepareFilters(array $filters): array
    {
        $resultFilters = [];
        if (!empty($filters['adCategory_id'])) {
            $adCategory = $this->adCategoryService->findOneById($filters['adCategory_id']);
            if (null !== $adCategory) {
                $resultFilters['adCategory'] = $adCategory;
            }
        }

        return $resultFilters;
    }// end prepareFilters()
}// end class
