<?php

/*
 * This file is part of the Symfony package.
 * (c) Stéphane BRIERE <stephanebriere@gdpweb.fr>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Tests\AppBundle\Manager;

use AppBundle\Entity\User;
use AppBundle\Manager\UserManager;
use AppBundle\Service\SPMailer;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Container\ContainerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class UserManagerTest extends KernelTestCase
{
    private $em;

    private $container;

    private $encoderFactory;

    private $mailer;

    private $user;
    /**
     * @var UserManager
     */
    private $userManager;

    protected function setUp()
    {
        $kernel = self::bootKernel();

        $this->encoderFactory = $kernel->getContainer()
            ->get('security.encoder_factory');
        $this->mailer = $this->createMock(SPMailer::class);
        $this->em = $this->createMock(EntityManagerInterface::class);

        $this->container = $this->createMock(ContainerInterface::class);
        $this->user = $this->createMock(User::class);
        $this->userManager = new UserManager($this->mailer, $this->em, $this->encoderFactory, $this->container);
    }

    public function testCreateToken()
    {
        $this->userManager->activeAccount($this->user);
        $this->assertNull($this->user->getToken());
    }

    public function testResetMail()
    {
        $result = $this->userManager->resetMail($this->user);
        $this->assertTrue($result instanceof User);
    }
}
