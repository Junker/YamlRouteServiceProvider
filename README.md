# YamlRouteServiceProvider

Silex Service provider for using YAML routing files

[![Latest Stable Version](https://poser.pugx.org/junker/yaml-route-service-provider/v/stable)](https://packagist.org/packages/junker/yaml-route-service-provider)
[![Total Downloads](https://poser.pugx.org/junker/yaml-route-service-provider/downloads)](https://packagist.org/packages/junker/yaml-route-service-provider)
[![Build Status](https://scrutinizer-ci.com/g/Junker/YamlRouteServiceProvider/badges/build.png?b=master)](https://scrutinizer-ci.com/g/Junker/YamlRouteServiceProvider/build-status/master)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/Junker/YamlRouteServiceProvider/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/Junker/YamlRouteServiceProvider/?branch=master)
[![License](https://poser.pugx.org/junker/yaml-route-service-provider/license)](https://packagist.org/packages/junker/yaml-route-service-provider)

## Requirements

silex 1.x

## Installation

The best way to install YamlRouteServiceProvider is to use a [Composer](https://getcomposer.org/download):

    php composer.phar require junker/yaml-route-service-provider

## Examples

```php
use Junker\Silex\Provider\YamlRouteServiceProvider;

$app->register(new YamlRouteServiceProvider('routes.yml'));

# or

$app->register(new YamlRouteServiceProvider('routes.yml', ['cache_dir' => '/tmp/routes_cache']));

```

Routes config example: 

```yaml
# routes.yml 

home:
    path: /
    defaults: { _controller: 'Acme\Controller\AppController::indexAction' }

articles.list:
    path: /articles
    defaults: { _controller: 'Acme\Controller\ArticlesController::indexAction' }

articles.view:
    path: /articles/{slug}
    defaults: { _controller: 'Acme\Controller\ArticlesController::viewAction' }
```

Controller example:

```php
# Acme\Controller\ArticlesController.php

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

namespace Acme\Controller;

class ArticlesController
{
	public function indexAction(Request $request, Application $app)
	{
		...

		return new Response($articles);
	}

	public function viewAction(Request $request, Application $app, $slug)
	{
		...

		return new Response($article);
	}
}
```

## Documentation

[Symfony Routing](http://symfony.com/doc/current/book/routing.html)
