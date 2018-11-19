<?php

/*
 * This file is part of the Symfony package.
 * (c) Stéphane BRIERE <stephanebriere@gdpweb.fr>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Tests\AppBundle\Manager;

use AppBundle\Entity\Image;
use AppBundle\Entity\Trick;
use AppBundle\Entity\User;
use AppBundle\Entity\Video;
use AppBundle\Manager\TrickManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class TrickManagerTest extends KernelTestCase
{
    /**
     * @var \Doctrine\ORM\EntityManager
     */
    private $em;
    /**
     * @var \Doctrine\ORM\EntityManager
     */
    private $emMock;

    /**
     * @var TrickManager
     */
    private $trickManager;
    /**
     * @var TrickManager
     */
    private $trickManagerMock;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $kernel = self::bootKernel();

        $this->em = $kernel->getContainer()
            ->get('doctrine')
            ->getManager();
        $this->trickManager = new TrickManager($this->em);

        $this->emMock = $this->createMock(EntityManagerInterface::class);
        $this->trickManagerMock = new TrickManager($this->emMock);
    }

    public function testGetAll()
    {
        $tricks = $this->trickManager->getAll();
        $this->assertTrue(10 === \count($tricks));
    }

    public function testGetListTricks()
    {
        $tricks = $this->trickManager->getListTricks();
        $this->assertTrue(10 === \count($tricks));
    }

    public function testCountTricks()
    {
        $tricks = $this->trickManager->countTricks();
        $this->assertTrue(10 === $tricks);
    }

    public function testSaveTrick()
    {
        $user = $this->createMock(User::class);
        $user->method('getUsername')->willReturn('admin56');
        $trick = new Trick();
        $this->trickManagerMock->saveTrick($trick, $user);
        $this->assertSame('admin56', $trick->getAuteur()->getUsername());
    }

    public function testAddImage()
    {
        $image = $this->createMock(Image::class);
        $trick = new Trick();
        $this->trickManagerMock->addImage($trick, $image);
        $this->assertSame($image, $trick->getImages()->first());
    }

    public function testAddVideo()
    {
        $video = $this->createMock(Video::class);
        $trick = new Trick();
        $this->trickManagerMock->addVideo($trick, $video);
        $this->assertSame($video, $trick->getVideos()->first());
    }

    /**
     * {@inheritdoc}
     */
    protected function tearDown()
    {
        parent::tearDown();

        $this->em->close();
        $this->em = null;
    }
}
