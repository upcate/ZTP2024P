<?php
/**
 * AdTest.
 */

/**
 * This test file is a part of project made as a part of the ZTP course completion.
 *
 * (c) Miłosz Świątek <milosz.swiatek@student.uj.edu.pl>
 */

namespace App\Tests\Entity;

use App\Entity\Ad;
use App\Entity\AdCategory;
use PhpUnit\Framework\TestCase;

/**
 * Class AdTest.
 */
class AdTest extends TestCase
{
    /**
     * Test setters and getters.
     */
    public function testSettersAndGetters(): void
    {
        // given
        $adCategory = $this->createAdCategory();
        $ad = new Ad();
        $date = new \DateTimeImmutable();
        $ad->setUsername('username');
        $ad->setEmail('email@email.com');
        $ad->setPhone(123456789);
        $ad->setText('text');
        $ad->setIsVisible(1);
        $ad->setTitle('title');
        $ad->setAdCategory($adCategory);
        $ad->setCreatedAt($date);
        $ad->setUpdatedAt($date);

        // then
        self::assertSame('username', $ad->getUsername());
        self::assertSame('email@email.com', $ad->getEmail());
        self::assertSame('text', $ad->getText());
        self::assertSame($adCategory, $ad->getAdCategory());
        self::assertSame($date, $ad->getCreatedAt());
        self::assertSame($date, $ad->getUpdatedAt());
        self::assertSame('123456789', $ad->getPhone());
        self::assertSame(1, $ad->getIsVisible());
        self::assertSame('title', $ad->getTitle());
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
}