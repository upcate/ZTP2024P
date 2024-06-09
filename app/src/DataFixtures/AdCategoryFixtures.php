<?php

/**
 * AdCategoryFixtures.
 */

namespace App\DataFixtures;

use App\Entity\AdCategory;
use DateTimeImmutable;

/**
 * Class AdCategoryFixtures.
 */
class AdCategoryFixtures extends AbstractBaseFixtures
{
    /**
     * Load data.
     */
    public function loadData(): void
    {
        $this->createMany(20, 'adCategories', function (int $i) {
            $adCategory = new AdCategory();
            $adCategory->setName($this->faker->unique()->word);
            $adCategory->setCreatedAt(
                DateTimeImmutable::createFromMutable(
                    $this->faker->dateTimeBetween('-100 days', '-1 days')
                )
            );
            $adCategory->setUpdatedAt(
                DateTimeImmutable::createFromMutable(
                    $this->faker->dateTimeBetween('-100 days', '-1 days')
                )
            );

            return $adCategory;
        });

        $this->manager->flush();
    }
}
