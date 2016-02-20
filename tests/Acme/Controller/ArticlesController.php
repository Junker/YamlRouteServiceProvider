<?php

namespace Acme\Controller;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ArticlesController
{
    public function indexAction(Request $request, Application $app)
    {
        return new Response('All Articles');
    }

    public function viewAction(Request $request, Application $app, $slug)
    {
        return new Response('Article ' . $slug);
    }
}