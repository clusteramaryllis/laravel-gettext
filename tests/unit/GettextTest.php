<?php

use Clusteramaryllis\Gettext\Gettext;

class GettextTest extends PHPUnit_Framework_TestCase
{
    protected $gettext;

    public function setUp()
    {
        $this->gettext = new Gettext();
    }

    public function testGettext()
    {
        $this->gettext->bindTextDomain('firstdomain', __DIR__."/../locale");
        $this->gettext->bindTextDomain('seconddomain', __DIR__."/../locale");

        $this->gettext->setLocale(LC_ALL, 'en_US.UTF-8');

        $this->assertEquals("Welcome to first domain", _("Welcome to first domain"));

        textdomain('seconddomain');
        $this->assertEquals("Welcome to first domain !", _("Welcome to first domain"));

        $this->gettext->setLocale(LC_ALL, 'id_ID.UTF-8');
        $this->assertEquals("Selamat datang di domain pertama", _("Welcome to first domain"));
    }
}
