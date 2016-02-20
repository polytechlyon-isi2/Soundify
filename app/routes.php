<?php

// Home page
$app->get('/', function () use ($app) {
    // $users = $app['dao.user']->FONCTION();
    return $app['twig']->render('index.html.twig'/*, array('articles' => $articles)*/);
});