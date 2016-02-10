<?php

namespace Junker\Silex\Provider;

use Silex\Application;
use Silex\ServiceProviderInterface;
use Symfony\Component\Config\ConfigCacheInterface;
use Symfony\Component\Config\ConfigCacheFactoryInterface;
use Symfony\Component\Config\ConfigCacheFactory;
use Symfony\Component\Routing\Loader\YamlFileLoader;
use Symfony\Component\Routing\RouteCollection;
use Symfony\Component\Config\FileLocator;

use Junker\Silex\PhpRouteCollectionDumper;


class YamlRouteServiceProvider implements ServiceProviderInterface
{
	protected $cache_dir;
	protected $debug;
	protected $file;

	protected $configCacheFactory;

	public function __construct($file, $options = NULL)
	{
		if (is_array($options))
		{
			if (isset($options['cache_dir']))
				$this->cache_dir = $options['cache_dir'];

			if (isset($options['debug']))
				$this->debug = $options['debug'];
		}

		$this->file = $file;
	}

	public function register(Application $app)
	{
		$app['routes'] = $app->share($app->extend('routes', function (RouteCollection $routes, Application $app)
		{
			if ($this->cache_dir)
			{
				$cache = $this->getConfigCacheFactory()->cache($this->cache_dir.'/RouteCollection.php',
					function (ConfigCacheInterface $cache) 
					{
						$collection = $this->loadRouteCollection();

						$content = PhpRouteCollectionDumper::dump($collection);

						$cache->write($content, $collection->getResources());
					}
				);

				$collection = include $cache->getPath();
			}
			else
			{
				$collection = $this->loadRouteCollection();
			}

			$routes->addCollection($collection);

			return $routes;
		}));
	}

	public function boot(Application $app) 
	{
	}

	private function getConfigCacheFactory()
	{
		if ($this->configCacheFactory === NULL) 
		{
			$this->configCacheFactory = new ConfigCacheFactory($this->debug);
		}

		return $this->configCacheFactory;
	}

	protected function loadRouteCollection()
	{

		$loader = new YamlFileLoader(new FileLocator(dirname($this->file)));

		$collection = $loader->load($this->file);

		return $collection;

	}		



}
