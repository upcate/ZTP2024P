<?php

/**
 * This test file is a part of project made as a part of the ZTP course completion.
 *
 * (c) Miłosz Świątek <milosz.swiatek@student.uj.edu.pl>
 */

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

/**
 * Class AppFixtures.
 */
class AppFixtures extends Fixture
{
    /**
     * Load function.
     *
     * @param ObjectManager $manager Object Manager
     */
    public function load(ObjectManager $manager): void
    {
        // $product = new Product();
        // $manager->persist($product);

        $manager->flush();
    }
}
