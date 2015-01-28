<?php

use Clusteramaryllis\Gettext\Manager;

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
        $this->assertEquals(array('en_US'), $this->manager->getLocales());
        $this->assertEquals('UTF-8', $this->manager->getEncoding());
        $this->assertFileExists($this->manager->getStoragePath());

        $this->manager->setDomain('gettext');
        $this->assertEquals('gettext', $this->manager->getDomain());
    }
}
