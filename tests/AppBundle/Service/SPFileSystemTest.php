<?php

/*
 * This file is part of the Symfony package.
 * (c) Stéphane BRIERE <stephanebriere@gdpweb.fr>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Tests\AppBundle;

use AppBundle\Service\SPFileSystem;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\HttpFoundation\File\File;

class SPFileSystemTest extends KernelTestCase
{
    /**
     * @var SPFileSystem
     */
    private $spFileSystem;

    private $appPath;

    protected function setUp()
    {
        $kernel = self::bootKernel();

        $this->spFileSystem = new SPFileSystem();
        $this->appPath = $kernel->getContainer()->getParameter('kernel.project_dir');
    }

    public function testUpload()
    {
        $file = $this->createMock(File::class);
        $file->method('getPath')->willReturn($this->appPath.'/src/AppBundle/DataFixtures/img');
        $file->method('getFilename')->willReturn('avatar-1.jpg');
        $this->spFileSystem->upload($file, 'web/avatar-essai.jpg');
    }

    public function testRemove()
    {
        $file = $this->createMock(File::class);
        $file->method('getPath')->willReturn($this->appPath.'/src/AppBundle/DataFixtures/img');
        $file->method('getFilename')->willReturn('essai.jpg');
        $this->spFileSystem->remove($file);
    }
}
