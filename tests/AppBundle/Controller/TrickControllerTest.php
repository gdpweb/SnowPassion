<?php

/*
 * This file is part of the Symfony package.
 * (c) Stéphane BRIERE <stephanebriere@gdpweb.fr>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Tests\AppBundle\Controller;

use AppBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\BrowserKit\Cookie;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

class TrickControllerTest extends WebTestCase
{
    /**
     * @var \Symfony\Bundle\FrameworkBundle\Client
     */
    private $client = null;

    public function setUp()
    {
        $this->client = static::createClient();
        $this->client->followRedirects();
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
            ['/', 200],
            ['/trick/mute', 200],
        ];
    }

    public function testEditAction()
    {
        $this->logIn();
        $this->client->request('GET', '/admin/edit/mute');
        $this->assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
    }

    public function testAddAction()
    {
        $this->logIn();
        $crawler = $this->client->request('GET', '/admin/add');
        $imagePath = __DIR__.'/../../../src/AppBundle/DataFixtures/img/avatar-1.jpg';
        $photo = new UploadedFile($imagePath,
            'photo.jpg',
            'image/jpeg',
            null
        );

        $form = $crawler->selectButton('Valider')->form();
        $values = $form->getPhpValues();
        $values['appbundle_trick']['images'][0]['file']->upload($photo);
        $values['appbundle_trick']['videos'][0]['url'] = 'https://www.youtube.com/embed/SQyTWk7OxSI';
        $values['appbundle_trick']['nom'] = 'Figure essai';
        $values['appbundle_trick']['description'] = 'Essai';

        $this->client->request($form->getMethod(), $form->getUri(), $values,
            $form->getPhpFiles());

        echo $this->client->getResponse();
        $this->assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
    }

    public function testDeleteAction()
    {
        $this->logIn();
        $this->client->request('GET', '/admin/delete/style-week');
        $this->assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
    }

    private function logIn()
    {
        $session = $this->client->getContainer()->get('session');

        $firewall = 'main';
        $em = $this->client->getContainer()->get('doctrine.orm.entity_manager');
        $userManager = $em->getRepository('AppBundle:User');
        /** @var User $user */
        $user = $userManager->findOneBy(['email' => 'admin@gdpweb.fr']);
        $token = new UsernamePasswordToken($user, $user->getPassword(), $firewall, $user->getRoles());
        $session->set('_security_'.$firewall, serialize($token));
        $session->save();

        $cookie = new Cookie($session->getName(), $session->getId());
        $this->client->getCookieJar()->set($cookie);
    }
}
