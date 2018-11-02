<?php
/**
 * Created by PhpStorm.
 * User: brieres
 * Date: 01/11/2018
 * Time: 18:37
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
        $this->assertEquals($expected, $this->client->getResponse()->getStatusCode());
    }

    public function urlProvider()
    {
        return array(
            array('/login', 200),
            array('/forgot', 200)
        );
    }
}