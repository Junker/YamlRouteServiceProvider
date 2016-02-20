<?php

use Silex\Application;
use Silex\WebTestCase;
use Junker\Silex\Provider\YamlRouteServiceProvider;


include __DIR__ . '/../Acme/Controller/ArticlesController.php';

class RouteTest extends WebTestCase
{
    const CONFIG_FILE = '/../res/routes.yml';
    const CACHE_PATH = '/tmp/cache_routes_123634f3d';


    public function createApplication()
    { 
        $app = new Application();
        
        $app['debug'] = TRUE;
        $app['session.test'] = TRUE;

        $app->register(new YamlRouteServiceProvider(__DIR__ . self::CONFIG_FILE, ['cache_dir' => self::CACHE_PATH]));


        return $app;
    }


    public function testRoutes()
    {
        $client = $this->createClient();


        $crawler = $client->request('GET', '/articles');

#        fwrite(STDOUT, $client->getResponse()->getContent());

        $this->assertTrue($client->getResponse()->isOk());
        $this->assertEquals($client->getResponse()->getContent(), 'All Articles');

        $crawler = $client->request('GET', '/articles/first');
        $this->assertTrue($client->getResponse()->isOk());
        $this->assertEquals($client->getResponse()->getContent(), 'Article first');
    }

    public function testCache()
    {
        $client = $this->createClient();

        $this->assertFileExists(self::CACHE_PATH . '/routes.cache.php');
        $this->assertFileExists(self::CACHE_PATH . '/routes.cache.php.meta');

        system("rm -rf " . escapeshellarg(self::CACHE_PATH));

        $crawler = $client->request('GET', '/articles/second');
        $this->assertTrue($client->getResponse()->isOk());
        $this->assertEquals($client->getResponse()->getContent(), 'Article second');

        $this->assertFileExists(self::CACHE_PATH . '/routes.cache.php');
        $this->assertFileExists(self::CACHE_PATH . '/routes.cache.php.meta');

    }

}
