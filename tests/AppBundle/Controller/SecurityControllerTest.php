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

    public function testLoginAction()
    {
        $crawler = $this->client->request('GET', '/login');
        $form = $crawler->selectButton('Valider')->form();
        $form['_username'] = 'admin56';
        $form['_password'] = 'admin56';
        $this->client->submit($form);
        $this->assertTrue($this->client->getResponse()->isRedirection());
    }

    public function testForgotAction()
    {
        $crawler = $this->client->request('GET', '/forgot');
        $form = $crawler->selectButton('Valider')->form();

        $form['_username'] = 'admin56';
        $this->client->submit($form);
        $this->assertTrue($this->client->getResponse()->isRedirect('/'));

        $form['_username'] = 'notexist';
        $this->client->submit($form);
        $this->assertTrue($this->client->getResponse()->isRedirect('/forgot'));
    }

    public function testLogoutAction()
    {
        $this->client->request('GET', '/connexion/logout');
        $this->assertTrue($this->client->getResponse()->isRedirect('http://localhost/'));
    }
}
