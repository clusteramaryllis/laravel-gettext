<?php

use Clusteramaryllis\Gettext\Driver\GettextDriver;

class GettextDriverTest extends PHPUnit_Framework_TestCase
{
    protected $driver;

    public function setUp()
    {
        include __DIR__.'/../config/config.php';

        $this->driver = (new GettextDriver())->forceEmulator($config['force_emulator']);
    }

    public function testFetchLocale()
    {
        $str1 = $this->driver->fetchLocale('LC_CTYPE=pl_PL.UTF-8;LC_NUMERIC=pl_PL.UTF-8;LC_TIME=pl_PL.UTF-8;LC_COLLATE=C;LC_MONETARY=pl_PL.UTF-8;LC_MESSAGES=pl_PL.UTF-8;LC_PAPER=pl_PL.UTF-8;LC_NAME=pl_PL.UTF-8;LC_ADDRESS=pl_PL.UTF-8;LC_TELEPHONE=pl_PL.UTF-8;LC_MEASUREMENT=pl_PL.UTF-8;LC_IDENTIFICATION=pl_PL.UTF-8');
        $str2 = $this->driver->fetchLocale('fr_FR.ISO-8859-1');

        $this->assertEquals("pl_PL.UTF-8", $str1);
        $this->assertEquals("fr_FR.ISO-8859-1", $str2);
    }
}
