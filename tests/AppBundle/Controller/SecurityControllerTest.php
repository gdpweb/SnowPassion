<?php

/*
 * This file is part of the Symfony package.
 * (c) Stéphane BRIERE <stephanebriere@gdpweb.fr>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Tests\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class SecurityControllerTest extends WebTestCase
{
    /**
     * @var \Symfony\Bundle\FrameworkBundle\Client
     */
    private $client = null;

    public function setUp()
    {
        $this->client = static::createClient();
    }

    /**
     * @dataProvider urlProvider
     */
    public function testPageIsSuccessful($url, $expected)
    {
        $this->client->request('GET', $url);
        $this->assertSame($expected, $this->client->getResponse()->getStatusCode());
    }

    public function urlProvider()
    {
        return [
            ['/login', 200],
            ['/forgot', 200],
        ];
    }
}
