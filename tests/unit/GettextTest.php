<?php

class GettextTest extends Orchestra\Testbench\TestCase
{
    protected $gettext;

    public function setUp()
    {
        parent::setUp();

        bindtextdomain('firstdomain', __DIR__."/../locale");
        bindtextdomain('seconddomain', __DIR__."/../locale");

        set_locale(LC_ALL, 'en_US.UTF-8');
    }

    public function getPackageProviders($app)
    {
        return ['Clusteramaryllis\Gettext\GettextServiceProvider'];
    }

    public function testGetText()
    {
        $this->assertEquals("Welcome to first domain", _("Welcome to first domain"));

        textdomain('seconddomain');

        $this->assertEquals(
            "Welcome to first domain !", 
            gettext("Welcome to first domain")
        );

        set_locale(LC_ALL, 'id_ID.UTF-8');

        $this->assertEquals(
            "Selamat datang di domain pertama", 
            gettext("Welcome to first domain")
        );
    }

    public function testDGetText()
    {
        $this->assertEquals(
            "Welcome to first domain", 
            dgettext("firstdomain", "Welcome to first domain")
        );
    }

    public function testNGetText()
    {
        $this->assertEquals("pig", ngettext("pig", "pigs", 1));
        $this->assertEquals("pigs", ngettext("pig", "pigs", 2));
    }

    public function testPGetText()
    {
        set_locale(LC_ALL, 'id_ID.UTF-8');

        textdomain('seconddomain');

        $this->assertEquals("rindu", pgettext("yearn", "miss"));
        $this->assertEquals("luput", pgettext("mishit", "miss"));
    }

    public function testNPGetText()
    {
        textdomain('seconddomain');

        $this->assertEquals(
            "mouses", 
            npgettext("device", "mouse", "mouses", 2)
        );
        $this->assertEquals(
            "mice", 
            npgettext("animal", "mouse", "mice", 2)
        );   
    }

    public function testDCGetText()
    {
        $this->assertEquals(
            "Welcome to first domain !",
            dcgettext("seconddomain", "Welcome to first domain", LC_MESSAGES)
        );
    }

    public function testDNGetText()
    {
        $this->assertEquals(
            "pig", 
            dngettext("firstdomain", "pig", "pigs", 1)
        );
        $this->assertEquals(
            "pigs", 
            dngettext("firstdomain", "pig", "pigs", 2)
        );
    }

    public function testDCNGetText()
    {
        $this->assertEquals(
            "pig", 
            dcngettext("firstdomain", "pig", "pigs", 1, LC_MESSAGES)
        );
        $this->assertEquals(
            "pigs", 
            dcngettext("firstdomain", "pig", "pigs", 2, LC_MESSAGES)
        );
    }

    public function testDPGetText()
    {
        set_locale(LC_ALL, 'id_ID.UTF-8');

        $this->assertEquals("rindu", dpgettext("seconddomain", "yearn", "miss"));
        $this->assertEquals("luput", dpgettext("seconddomain", "mishit", "miss"));
    }

    public function testDNPGetText()
    {
        $this->assertEquals(
            "mouses", 
            dnpgettext("seconddomain", "device", "mouse", "mouses", 2)
        );
        $this->assertEquals(
            "mice", 
            dnpgettext("seconddomain", "animal", "mouse", "mice", 2)
        );   
    }

    public function testDCNPGetText()
    {
        $this->assertEquals(
            "mouses", 
            dcnpgettext("seconddomain", "device", "mouse", "mouses", 2, LC_MESSAGES)
        );
        $this->assertEquals(
            "mice", 
            dcnpgettext("seconddomain", "animal", "mouse", "mice", 2, LC_MESSAGES)
        );   
    }
}
