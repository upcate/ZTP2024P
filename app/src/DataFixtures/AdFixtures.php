<?php

/**
 * AdFixtures.
 */

namespace App\DataFixtures;

use App\Entity\Ad;
use DateTimeImmutable;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

/**
 * Class AdFixtures.
 */
class AdFixtures extends AbstractBaseFixtures implements DependentFixtureInterface
{
    /**
     * Load data.
     */
    public function loadData(): void
    {
        if (!$this->manager instanceof \Doctrine\Persistence\ObjectManager || !$this->faker instanceof \Faker\Generator) {
            return;
        }

        $this->createMany(100, 'ads', function (int $i) {
            $ad = new Ad();
            $ad->setTitle($this->faker->sentence);
            $ad->setCreatedAt(
                DateTimeImmutable::createFromMutable(
                    $this->faker->dateTimeBetween('-100 days', '-1 days')
                )
            );
            $ad->setUpdatedAt(
                DateTimeImmutable::createFromMutable(
                    $this->faker->dateTimeBetween('-100 days', '-1 days')
                )
            );

            $ad->setEmail($this->faker->email);

            $ad->setPhone($this->faker->numerify('4########'));

            $ad->setUsername($this->faker->userName);

            $ad->setText($this->faker->text(150));

            $ad->setIsVisible($this->faker->numberBetween(0, 1));

            $adCategory = $this->getRandomReference('adCategories');
            $ad->setAdCategory($adCategory);

            return $ad;
        });

        $this->manager->flush();
    }

    /**
     * Get Dependencies.
     *
     * @return string[] Dependecies
     */
    public function getDependencies(): array
    {
        return [AdCategoryFixtures::class];
    }
}
