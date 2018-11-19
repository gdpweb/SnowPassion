<?php

/*
 * This file is part of the Symfony package.
 * (c) Stéphane BRIERE <stephanebriere@gdpweb.fr>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Tests\AppBundle\Controller;

use AppBundle\Entity\Image;
use AppBundle\Entity\User;
use AppBundle\Entity\Video;
use Doctrine\ORM\EntityManager;
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
    /**
     * @var EntityManager
     */
    private $em;

    public function setUp()
    {
        $this->client = static::createClient();
        $this->em = $this->client->getContainer()->get('doctrine.orm.entity_manager');
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
            ['/listTricks', 200],
            ['/trick/mute', 200],
            ['/comments/mute/page/1', 200],
            ['/admin/add', 302],
            ['/admin/edit/mute', 302],
            ['/admin/delete/mute', 302],
            ['/admin/add_image/mute', 302],
            ['/admin/update_image/mute', 302],
            ['/admin/add_video/mute', 302],
            ['/admin/update_video/mute', 302],
        ];
    }

    public function testEditAction()
    {
        $this->logIn();
        $this->client->request('GET', '/admin/edit/mute');
        $this->assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
    }

    public function testAddComment()
    {
        $this->logIn();
        $crawler = $this->client->request('GET', '/trick/mute');
        $form = $crawler->selectButton('Envoyer')->form();
        $form['appbundle_comment[message]'] = 'Bon article';
        $this->client->submit($form);

        $response = $this->client->getResponse();
        $this->assertNotContains('help-block', $response->getContent());
    }

    public function testAddAction()
    {
        $this->logIn();
        $crawler = $this->client->request('GET', '/admin/add');

        $srcFile = __DIR__.'/../../../src/AppBundle/DataFixtures/img/img-trick-1.jpg';
        $imageTmpPath = __DIR__.'/../../../img-trick-1.jpg';

        copy($srcFile, $imageTmpPath);

        $photo = [
            'tmp_name' => $imageTmpPath,
            'name' => 'trick-1.jpg',
            'type' => 'image/jpeg',
            'error' => 0,
            'size' => 0,
        ];

        $form = $crawler->selectButton('Valider')->form();

        $values = $form->getPhpValues();

        $values['appbundle_trick']['videos'][0]['url'] = 'https://www.youtube.com/embed/SQyTWk7OxSI';
        $values['appbundle_trick']['nom'] = 'Figure - '.uniqid();
        $values['appbundle_trick']['description'] = 'Oportunum est, ut arbitror, explanare nunc causam, quae ad 
        exitium praecipitem Aginatium inpulit iam inde a priscis maioribus nobilem, ut locuta est pertinacior fama. 
        nec enim super hoc ulla documentorum rata est fides.';

        $files = $form->getFiles();
        $files['appbundle_trick']['images'][0]['file'] = $photo;

        $this->client->request($form->getMethod(), $form->getUri(), $values, $files);

        $this->assertTrue($this->client->getResponse()->isRedirect());
    }

    public function testDeleteAction()
    {
        $this->logIn();
        $trick = $this->em->getRepository('AppBundle:Trick')->findOneBy([], ['id' => 'DESC']);

        $crawler = $this->client->request('GET', '/admin/delete/'.$trick->getSlug());
        $form = $crawler->selectButton('Supprimer')->form();
        $this->client->submit($form);
        $this->assertTrue($this->client->getResponse()->isRedirect('/'));
    }

    public function testAddImageAction()
    {
        $this->logIn();
        $crawler = $this->client->request('GET', '/admin/add_image/mute');
        $srcFile = __DIR__.'/../../../src/AppBundle/DataFixtures/img/img-trick-1.jpg';
        $photo = new UploadedFile($srcFile,
            'trick-1.jpg',
            'image/jpeg',
            null
        );
        $form = $crawler->selectButton('Ajouter')->form();
        $form['appbundle_image[file]'] = $photo;
        $this->client->submit($form);
        $this->assertTrue($this->client->getResponse()->isRedirect('/admin/edit/mute'));
    }

    public function testUpdateImageAction()
    {
        $this->logIn();
        $trick = $this->em->getRepository('AppBundle:Trick')->findOneBy(['slug' => 'mute']);
        /** @var Image $image */
        $image = $trick->getImages()[0];
        $crawler = $this->client->request('GET', '/admin/update_image/'.$image->getId());
        $srcFile = __DIR__.'/../../../src/AppBundle/DataFixtures/img/img-trick-2.jpg';
        $photo = new UploadedFile($srcFile,
            'trick-2.jpg',
            'image/jpeg',
            null
        );
        $form = $crawler->selectButton('Modifier')->form();
        $form['appbundle_image[file]'] = $photo;
        $this->client->submit($form);

        $response = $this->client->getResponse();
        $this->assertNotContains('help-block', $response->getContent());
    }

    public function testDeleteImageAction()
    {
        $this->logIn();
        $trick = $this->em->getRepository('AppBundle:Trick')->findOneBy(['slug' => 'mute']);
        /** @var Image $image */
        $image = $trick->getImages()[0];
        $crawler = $this->client->request('GET', '/admin/trick/mute/delete_image/'.$image->getId());
        $form = $crawler->selectButton('Supprimer')->form();
        $this->client->submit($form);
        $this->assertTrue($this->client->getResponse()->isRedirect('/admin/edit/mute'));
    }

    public function testAddVideoAction()
    {
        $this->logIn();
        $crawler = $this->client->request('GET', '/admin/add_video/mute');

        $form = $crawler->selectButton('Ajouter')->form();
        $form['appbundle_video[url]'] = 'https://www.youtube.com/embed/SQyTWk7OxSI';
        $this->client->submit($form);

        $response = $this->client->getResponse();
        $this->assertNotContains('help-block', $response->getContent());
    }

    public function testUpdateVideoAction()
    {
        $this->logIn();
        $trick = $this->em->getRepository('AppBundle:Trick')->findOneBy(['slug' => 'mute']);
        /** @var Video $video */
        $video = $trick->getVideos()[0];
        $crawler = $this->client->request('GET', '/admin/update_video/'.$video->getId());
        $form = $crawler->selectButton('Modifier')->form();
        $form['appbundle_video[url]'] = 'https://www.youtube.com/embed/SQyTWk7OxSI';
        $this->client->submit($form);

        $response = $this->client->getResponse();
        $this->assertNotContains('help-block', $response->getContent());
    }

    public function testDeleteVideoAction()
    {
        $this->logIn();
        $trick = $this->em->getRepository('AppBundle:Trick')->findOneBy(['slug' => 'mute']);
        /** @var Video $video */
        $video = $trick->getVideos()[0];
        $crawler = $this->client->request('GET', '/admin/trick/mute/delete_video/'.$video->getId());
        $form = $crawler->selectButton('Supprimer')->form();
        $this->client->submit($form);

        $response = $this->client->getResponse();
        $this->assertNotContains('help-block', $response->getContent());
    }

    private function logIn()
    {
        $session = $this->client->getContainer()->get('session');

        $firewall = 'main';

        $userManager = $this->em->getRepository('AppBundle:User');
        /** @var User $user */
        $user = $userManager->findOneBy(['username' => 'admin56']);
        $user->setIsActive(true);
        $token = new UsernamePasswordToken($user, $user->getPassword(), $firewall, $user->getRoles());
        $session->set('_security_'.$firewall, serialize($token));
        $session->save();

        $cookie = new Cookie($session->getName(), $session->getId());
        $this->client->getCookieJar()->set($cookie);
    }
}
