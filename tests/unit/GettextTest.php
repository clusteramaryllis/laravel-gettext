<?php

use Clusteramaryllis\Gettext\Gettext;
use Clusteramaryllis\Gettext\Driver\GettextDriver;

class GettextTest extends PHPUnit_Framework_TestCase
{
    protected $gettext;

    public function setUp()
    {
        include __DIR__.'/../config/config.php';

        $this->gettext = new Gettext(
            (new GettextDriver())->forceEmulator($config['force_emulator'])
        );

        $this->gettext->bindTextDomain('firstdomain', __DIR__."/../locale");
        $this->gettext->bindTextDomain('seconddomain', __DIR__."/../locale");
        
        $this->gettext->setLocale(LC_ALL, 'en_US.UTF-8');
    }

    public function testGetText()
    {
        $this->assertEquals(
            "Welcome to first domain",
            $this->gettext->getText("Welcome to first domain")
        );

        $this->gettext->textDomain('seconddomain');

        $this->assertEquals(
            "Welcome to first domain !",
            $this->gettext->getText("Welcome to first domain")
        );

        $this->gettext->setLocale(LC_ALL, 'id_ID.UTF-8');

        $this->assertEquals(
            "Selamat datang di domain pertama",
            $this->gettext->getText("Welcome to first domain")
        );
    }

    public function testDGetText()
    {
        $this->assertEquals(
            "Welcome to first domain",
            $this->gettext->dGetText("firstdomain", "Welcome to first domain")
        );
    }

    public function testNGetText()
    {
        $this->assertEquals("pig", $this->gettext->nGetText("pig", "pigs", 1));
        $this->assertEquals("pigs", $this->gettext->nGetText("pig", "pigs", 2));
    }

    public function testPGetText()
    {
        $this->gettext->setLocale(LC_ALL, 'id_ID.UTF-8');

        $this->gettext->textDomain('seconddomain');

        $this->assertEquals("rindu", $this->gettext->pGetText("yearn", "miss"));
        $this->assertEquals("luput", $this->gettext->pGetText("mishit", "miss"));
    }

    public function testNPGetText()
    {
        $this->gettext->textDomain('seconddomain');

        $this->assertEquals(
            "mouses",
            $this->gettext->nPGetText("device", "mouse", "mouses", 2)
        );
        $this->assertEquals(
            "mice",
            $this->gettext->nPGetText("animal", "mouse", "mice", 2)
        );
    }

    public function testDCGetText()
    {
        $this->assertEquals(
            "Welcome to first domain !",
            $this->gettext->dCGetText("seconddomain", "Welcome to first domain", LC_MESSAGES)
        );
    }

    public function testDNGetText()
    {
        $this->assertEquals(
            "pig",
            $this->gettext->dNGetText("firstdomain", "pig", "pigs", 1)
        );
        $this->assertEquals(
            "pigs",
            $this->gettext->dNGetText("firstdomain", "pig", "pigs", 2)
        );
    }

    public function testDCNGetText()
    {
        $this->assertEquals(
            "pig",
            $this->gettext->dCNGetText("firstdomain", "pig", "pigs", 1, LC_MESSAGES)
        );
        $this->assertEquals(
            "pigs",
            $this->gettext->dCNGetText("firstdomain", "pig", "pigs", 2, LC_MESSAGES)
        );
    }

    public function testDPGetText()
    {
        $this->gettext->setLocale(LC_ALL, 'id_ID.UTF-8');

        $this->assertEquals("rindu", $this->gettext->dPGetText("seconddomain", "yearn", "miss"));
        $this->assertEquals("luput", $this->gettext->dPGetText("seconddomain", "mishit", "miss"));
    }

    public function testDNPGetText()
    {
        $this->assertEquals(
            "mouses",
            $this->gettext->dNPGetText("seconddomain", "device", "mouse", "mouses", 2)
        );
        $this->assertEquals(
            "mice",
            $this->gettext->dNPGetText("seconddomain", "animal", "mouse", "mice", 2)
        );
    }

    public function testDCNPGetText()
    {
        $this->assertEquals(
            "mouses",
            $this->gettext->dCNPGetText("seconddomain", "device", "mouse", "mouses", 2, LC_MESSAGES)
        );
        $this->assertEquals(
            "mice",
            $this->gettext->dCNPGetText("seconddomain", "animal", "mouse", "mice", 2, LC_MESSAGES)
        );
    }
}
