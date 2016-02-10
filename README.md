#YamlRouteServiceProvider
Silex Service provider for using YAML routing files


##Requirements
silex 1.x

##Installation
The best way to install YamlRouteServiceProvider is to use a [Composer](https://getcomposer.org/download):

    php composer.phar require junker/yaml-route-service-provider

##Examples

```php
use Junker\Silex\Provider\YamlRouteServiceProvider;

$app->register(new YamlRouteServiceProvider('routes.yml');

#or

$app->register(new YamlRouteServiceProvider('routes.yml', ['cache_dir' => '/tmp/routes_cache', 'debug' => $app['debug']]));


```


routes.yml ([Instruction](http://symfony.com/doc/current/book/routing.html))
```yaml

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

ArticlesController.php
```php
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

##Documentation
[Symfony Routing](http://symfony.com/doc/current/book/routing.html)