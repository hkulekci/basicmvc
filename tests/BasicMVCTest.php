<?php
namespace tests;

use BasicMVC\BasicMVC;

class BasicMVC_Test extends BaseCase
{
    protected $session;
    protected $twig;
    protected $app;
    protected $app_dir;
    protected $basicmvc;

    protected function setUp()
    {
        $this->app_dir = __DIR__;
        $twig = new \Slim\Views\Twig();
        $this->app = new \Slim\Slim(
            array(
                'mode'              => 'development', // development, test, and production
                'debug'             => true,
                'view'              => $this->twig
                )
            );
        $this->basicmvc = new BasicMVC($app, array(
            "controllers_path"   => $this->app_dir,
            "models_path"        => $this->app_dir,
            "library_path"       => $this->app_dir
        ));
    }

    public function testNamespace()
    {
        $this->assertNotNull($this->basicmvc);
    }
}