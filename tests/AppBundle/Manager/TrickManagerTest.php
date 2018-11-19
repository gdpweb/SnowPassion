<?php

/*
 * This file is part of the Symfony package.
 * (c) Stéphane BRIERE <stephanebriere@gdpweb.fr>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Tests\AppBundle\Manager;

use AppBundle\Manager\TrickManager;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class TrickManagerTest extends KernelTestCase
{
    /**
     * @var \Doctrine\ORM\EntityManager
     */
    private $em;
    /**
     * @var TrickManager
     */
    private $trickManager;

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
    }

    public function testGetAll()
    {
        $tricks = $this->trickManager->getAll();
        $this->assertTrue(\count($tricks) >= 1);
    }

    public function testGetListTricks()
    {
        $tricks = $this->trickManager->getListTricks();
        $this->assertTrue(\count($tricks) >= 1);
    }

    public function testCountTricks()
    {
        $tricks = $this->trickManager->countTricks();
        $this->assertTrue($tricks >= 1);
    }

    /**
     * {@inheritdoc}
     */
    protected function tearDown()
    {
        parent::tearDown();

        $this->em->close();
        $this->em = null; // avoid memory leaks
    }
}
