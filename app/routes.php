<?php
use Symfony\Component\HttpFoundation\Request;

// Home page
$app->get('/', function () use ($app) {
    $categories = $app['dao.category']->findAll();
    return $app['twig']->render('index.html.twig', array('categories' => $categories));
})->bind('home');


// Add category page
$app->get('/addCategory', function () use ($app) {
    $categories = $app['dao.category']->findAll();
    return $app['twig']->render('addCategory.html.twig', array('categories' => $categories));
})->bind('addCategory');

// Category page
$app->get('/category/{id}', function ($id) use ($app) {
    $categories = $app['dao.category']->findAll();
    $category = $app['dao.category']->find($id);
    $products = $app['dao.product']->findAllByCategory($id);
    return $app['twig']->render('category.html.twig', array('category' => $category, 'products' => $products,'categories' => $categories));
})->bind('category');

// Login form
$app->get('/login', function(Request $request) use ($app) {
    return $app['twig']->render('login.html.twig', array(
        'error'         => $app['security.last_error']($request),
        'last_username' => $app['session']->get('_security.last_username'),
    ));
})->bind('login');

// Admin home page
$app->get('/admin', function() use ($app) {
    $categories = $app['dao.category']->findAll();
    $products = $app['dao.product']->findAll();
    $users = $app['dao.user']->findAll();
    return $app['twig']->render('admin.html.twig', array(
        'categories' => $categories,
        'products' => $products,
        'users' => $users));
})->bind('admin');