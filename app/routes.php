<?php

// Home page
$app->get('/', function () use ($app) {
    $categories = $app['dao.category']->findAll();
    return $app['twig']->render('index.html.twig', array('categories' => $categories));
})->bind('home');

// Object Details example
/*$app->get('/article/{id}', function ($id) use ($app) {
    $article = $app['dao.article']->find($id);
    $comments = $app['dao.comment']->findAllByArticle($id);
    return $app['twig']->render('article.html.twig', array('article' => $article, 'comments' => $comments));
})->bind('article');
*/

$app->get('/category/{id}', function ($id) use ($app) {
    $category = $app['dao.category']->find($id);
    $products = $app['dao.product']->findAllByCategory($id);
    return $app['twig']->render('category.html.twig', array('category' => $category, 'products' => $products));
})->bind('category');