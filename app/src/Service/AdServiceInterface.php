<?php

/**
 * AdServiceInterface.
 */

namespace App\Service;

use App\Entity\Ad;
use Knp\Component\Pager\Pagination\PaginationInterface;

/**
 * Interface AdServiceInterface.
 */
interface AdServiceInterface
{
    /**
     * Get paginated list.
     *
     * @param int $page Number of page
     *
     * @return PaginationInterface Pagination Interface
     */
    public function getPaginatedList(int $page): PaginationInterface;

    /**
     * Save.
     *
     * @param Ad $ad Ad Entity
     */
    public function save(Ad $ad): void;

    /**
     * Save on creation by admin.
     *
     * @param Ad $ad Ad Entity
     */
    public function saveOnCreateAdm(Ad $ad): void;

    /**
     * Save on creation by user.
     *
     * @param Ad $ad Ad Entity
     */
    public function saveOnCreateUs(Ad $ad): void;

    /**
     * Make ad visible.
     *
     * @param Ad $ad Ad Entity
     */
    public function makeVisible(Ad $ad): void;

    /**
     * Delete.
     *
     * @param Ad $ad Ad Entity
     */
    public function delete(Ad $ad): void;
}
