<?php

use Clusteramaryllis\Gettext\Repositories\Manager;

class ManagerTest extends PHPUnit_Framework_TestCase
{
    protected $manager;

    public function setUp()
    {
        include __DIR__.'/../config/config.php';

        $this->manager = new Manager($config);
    }

    public function testGetterAndSetter()
    {
        $this->assertEquals(
            [
                'en' => [
                    'locale' => 'en_US',
                    'encoding' => 'utf-8',
                    'plural_forms' => "nplurals=2; plural=(n != 1);"
                ]
            ],
            $this->manager->getLanguages()
        );
        $this->assertFileExists($this->manager->getStoragePath());

        $this->manager->setDomain('gettext');
        $this->assertEquals('gettext', $this->manager->getDomain());
    }
}
