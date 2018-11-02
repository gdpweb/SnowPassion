<?php
/**
 * Created by PhpStorm.
 * User: brieres
 * Date: 01/11/2018
 * Time: 18:37
 */

namespace Tests\AppBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Response;

class UserControllerTest extends WebTestCase
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

            array('/register', 200),
            array('/reset/123456', 302),
            array('/validate/123456', 302)
        );
    }

    public function testRegisterAction()
    {

        $this->client->followRedirects();
        $crawler = $this->client->request('GET', '/register');
        $imagePath = __DIR__ . '/../../../src/AppBundle/DataFixtures/img/avatar-1.jpg';
        $photo = new UploadedFile($imagePath,
            'photo.jpg',
            'image/jpeg',
            null
        );

        $form = $crawler->selectButton('Valider')->form();
        $nbAlea = rand(1, 99999);
        $form['appbundle_user[username]'] = 'User '.$nbAlea;
        $form['appbundle_user[password]'] = 'motdepasse';
        $form['appbundle_user[email]'] = 'mail' . $nbAlea . '@gdpweb.fr';
        $form['appbundle_user[image][file]'] = $photo;
        $this->client->submit($form);
        // echo $this->client->getResponse();
        $this->assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());

    }
}