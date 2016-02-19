<?php

namespace Junker\Silex;

use Symfony\Component\Routing\RouteCollection;

class PhpRouteCollectionDumper
{
    public static function dump(RouteCollection $collection)
    {
        $content = '<?php use Symfony\Component\Routing\Route; use Symfony\Component\Routing\RouteCollection; $r = new RouteCollection();'.PHP_EOL;

        foreach ($collection->all() as $name => $route) {
            $content .= sprintf('$z = new Route(""); $z->unserialize(\'%s\'); $r->add("%s", $z);', $route->serialize(), $name).PHP_EOL;
        }

        $content .= 'return $r;';

        return $content;
    }
}
