<?php

/*
 * This file is part of the Symfony package.
 * (c) Stéphane BRIERE <stephanebriere@gdpweb.fr>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Tests\AppBundle\Controller;

use AppBundle\Entity\User;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class UserControllerTest extends WebTestCase
{
    /**
     * @var \Symfony\Bundle\FrameworkBundle\Client
     */
    private $client = null;
    /**
     * @var EntityManager
     */
    private $em;
    private $newUser;
    /**
     * @var User
     */
    protected $user;

    public function setUp()
    {
        $this->client = static::createClient();
        $this->em = $this->client->getContainer()->get('doctrine.orm.entity_manager');
        $this->newUser = uniqid();
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
            ['/register', 200],
            ['/reset/123456', 302],
            ['/validate/123456', 302],
        ];
    }

    public function testRegisterValidateAction()
    {
        $crawler = $this->client->request('GET', '/register');
        $srcFile = __DIR__.'/../../../src/AppBundle/DataFixtures/img/avatar-1.jpg';

        $photo = new UploadedFile($srcFile,
            'avatar-1.jpg',
            'image/jpeg',
            null
        );

        $form = $crawler->selectButton('Valider')->form();
        $form['appbundle_user[username]'] = $this->newUser;
        $form['appbundle_user[password]'] = $this->newUser;
        $form['appbundle_user[email]'] = $this->newUser.'@gdpweb.fr';
        $form['appbundle_user[image][file]'] = $photo;
        $this->client->submit($form);
        $userManager = $this->em->getRepository('AppBundle:User');

        $this->user = $userManager->findOneBy(['username' => $this->newUser]);
        $this->assertTrue($this->client->getResponse()->isRedirect('/'));
        $tokenUser = $this->user->getToken();
        $this->client->request('GET', '/validate/'.$tokenUser);
        $this->assertTrue($this->client->getResponse()->isRedirect());
    }

    public function testResetAction()
    {
        $userManager = $this->em->getRepository('AppBundle:User');
        /** @var User $user */
        $user = $userManager->findOneBy(['username' => 'admin56']);
        $tokenUser = $user->getToken();
        $crawler = $this->client->request('GET', '/reset/'.$tokenUser);
        $form = $crawler->selectButton('Réinitialiser')->form();
        $form['appbundle_user[email]'] = 'admin56@gdpweb.fr';
        $form['appbundle_user[password]'] = 'admin56';
        $this->client->submit($form);
        $this->assertTrue($this->client->getResponse()->isRedirect('/'));
    }
}
