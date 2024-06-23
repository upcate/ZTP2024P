<?php
/**
 * MainControllerTest/
 */

/**
 * This test file is a part of project made as a part of the ZTP course completion.
 *
 * (c) Miłosz Świątek <milosz.swiatek@student.uj.edu.pl>
 */

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Class MainControllerTest.
 */
class MainControllerTest extends WebTestCase
{
    /**
     * Set up function.
     */
    public function setUp(): void
    {
        $this->httpClient = static::createClient();
    }

    /**
     * Test main page route.
     */
    public function testMainRoute(): void
    {
        // given
        $expectedStatusCode = 200;

        // when
        $this->httpClient->request('GET', '/main');
        $statusCode = $this->httpClient->getResponse()->getStatusCode();

        // then
        $this->assertEquals($expectedStatusCode, $statusCode);
    }
}
