<?php

/**
 * AdCategoryServiceInterface,.
 */

namespace App\Service;

use App\Entity\AdCategory;
use Knp\Component\Pager\Pagination\PaginationInterface;

/**
 * Interface AdCategoryServiceInterface.
 */
interface AdCategoryServiceInterface
{
    /**
     * Get paginated list.
     *
     * @param int $page Number of page
     *
     * @return PaginationInterface Pagination interface
     */
    public function getPaginatedList(int $page): PaginationInterface;

    /**
     * Save.
     *
     * @param AdCategory $adCategory AdCategory Entity
     */
    public function save(AdCategory $adCategory): void;

    /**
     * Delete.
     *
     * @param AdCategory $adCategory AdCategory Entity
     */
    public function delete(AdCategory $adCategory): void;

    /**
     * Check can be deleted.
     *
     * @param AdCategory $adCategory AdCategory Entity
     *
     * @return bool Bool return
     */
    public function canBeDeleted(AdCategory $adCategory): bool;
}
