<?php
/**
 * AcCategoryTest.
 */

namespace App\Tests\Entity;

use App\Entity\AdCategory;
use PHPUnit\Framework\TestCase;

/**
 * Class AdCategoryTest.
 */
class AdCategoryTest extends TestCase
{
    /**
     * Test setters and getters.
     */
    public function testSettersAndGetters(): void
    {
        // given
        $adCategory = new AdCategory();
        $date = new \DateTimeImmutable();
        $adCategory->setCreatedAt($date);
        $adCategory->setUpdatedAt($date);
        $adCategory->setName('name');
        $adCategory->setSlug('slug');

        // then
        self::assertEquals($date, $adCategory->getCreatedAt());
        self::assertEquals($date, $adCategory->getUpdatedAt());
        self::assertEquals('name', $adCategory->getName());
        self::assertEquals('slug', $adCategory->getSlug());
    }
}
