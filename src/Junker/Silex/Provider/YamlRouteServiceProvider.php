<?php

namespace Junker\Silex\Provider;

use Silex\Application;
use Silex\ServiceProviderInterface;
use Symfony\Component\Config\ConfigCacheInterface;
use Symfony\Component\Config\ConfigCacheFactory;
use Symfony\Component\Routing\Loader\YamlFileLoader;
use Symfony\Component\Routing\RouteCollection;
use Symfony\Component\Config\FileLocator;
use Junker\Silex\PhpRouteCollectionDumper;

class YamlRouteServiceProvider implements ServiceProviderInterface
{
    protected $cacheDirPath;
    protected $configFilePath;
    protected $configCacheFactory;

    /**
     * @param string     $configFilePath Path to config file
     * @param null|array $options        Provider options
     */
    public function __construct($configFilePath, $options = null)
    {
        if (is_array($options)) {
            if (isset($options['cache_dir'])) {
                $this->cacheDirPath = $options['cache_dir'];
            }
        }

        $this->configFilePath = $configFilePath;
    }

    public function register(Application $app)
    {
        $app['routes'] = $app->share($app->extend('routes', function (RouteCollection $routes, Application $app) {
            if ($this->cacheDirPath) {
                $cache = $this->getConfigCacheFactory($app['debug'])->cache($this->cacheDirPath.'/routes.cache.php',
                    function (ConfigCacheInterface $cache) {
                        $collection = $this->loadRouteCollection();

                        $content = PhpRouteCollectionDumper::dump($collection);

                        $cache->write($content, $collection->getResources());
                    }
                );

                $collection = include $cache->getPath();
            } else {
                $collection = $this->loadRouteCollection();
            }

            $routes->addCollection($collection);

            return $routes;
        }));
    }

    public function boot(Application $app)
    {
    }

    /**
     * @param bool $debug Is debug mode enabled
     *
     * @return ConfigCacheFactory
     */
    private function getConfigCacheFactory($debug)
    {
        if ($this->configCacheFactory === null) {
            $this->configCacheFactory = new ConfigCacheFactory($debug);
        }

        return $this->configCacheFactory;
    }

    protected function loadRouteCollection()
    {
        $loader = new YamlFileLoader(new FileLocator(dirname($this->configFilePath)));

        $collection = $loader->load($this->configFilePath);

        return $collection;
    }
}
