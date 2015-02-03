<?php

use Illuminate\Filesystem\Filesystem;
use Illuminate\View\Compilers\BladeCompiler;
use Clusteramaryllis\Gettext\Repositories\PoGenerator;

class PoGeneratorTest extends PHPUnit_Framework_TestCase
{
    protected $config;

    protected $files;

    protected $generator;

    public function setUp()
    {
        include __DIR__.'/../config/config.php';

        $this->config    = $config;
        $this->files     = new Filesystem();
        $this->generator = new PoGenerator($this->files, $this->config['base_path']);
    }

    public function testStripPath()
    {
        $path  = $this->generator->stripPath(__DIR__, __DIR__.'/..');
        $paths = $this->generator->stripPath(array(__DIR__, __DIR__.'/../config'), __DIR__.'/..');

        $this->assertEquals('unit', $path);
        $this->assertEquals(array('unit', 'config'), $paths);
    }

    public function testCompileBladeViews()
    {
        $compiler = new BladeCompiler(new Filesystem(), $this->config['storage_path']);

        $this->generator->compileBladeViews($this->config['paths'], $this->config['storage_path']);

        foreach ($this->config['paths'] as $path) {
            $files = glob(realpath($path).'/{,**/}*.php', GLOB_BRACE);

            foreach ($files as $file) {
                $contents = $this->files->get($file);

                $this->assertEquals(
                    $compiler->compileString($contents),
                    $this->files->get($this->config['storage_path']."/".md5($file).".php")
                );
            }
        }
    }

    public function testPreparePoContent()
    {
        $timestamp = date_format(
            date_create_from_format("Y-m-d H:iO", "2015-01-17 03:29+0100"),
            "Y-m-d H:iO"
        );

        $content = $this->generator->preparePoContent(
            $this->config['paths'],
            $this->config['storage_path'],
            $this->config['destination_path'],
            'en_US',
            'UTF-8',
            $this->config['project_name'],
            $this->config['translator'],
            $this->config['keywords'],
            "nplurals=2; plural=(n != 1);",
            $timestamp
        );

        $this->assertEquals($this->files->get(__DIR__.'/../locale/en_US/LC_MESSAGES/test.po'), $content);
    }

    public function testAddLocale()
    {
        $content = $this->generator->addLocale(
            $this->config['paths'],
            $this->config['storage_path'],
            $this->config['destination_path'],
            $this->config['domain'],
            'en_US',
            'UTF-8',
            $this->config['project_name'],
            $this->config['translator'],
            $this->config['keywords'],
            "nplurals=2; plural=(n != 1);"
        );

        $this->assertFileExists(__DIR__.'/../locale/en_US/LC_MESSAGES/'.$this->config['domain'].'.po');
    }

    public function testUpdateLocale()
    {
        $content = $this->generator->updateLocale(
            $this->config['paths'],
            $this->config['storage_path'],
            $this->config['destination_path'],
            $this->config['domain'],
            'en_US',
            'UTF-8',
            $this->config['project_name'],
            $this->config['translator'],
            $this->config['keywords'],
            "nplurals=2; plural=(n != 1);"
        );

        $filename = __DIR__.'/../locale/en_US/LC_MESSAGES/'.$this->config['domain'].'.po';

        $this->assertFileExists($filename);

        $this->files->delete($filename);
        $this->files->deleteDirectory($this->config['storage_path'].'/'.$this->config['domain']);
    }
}
