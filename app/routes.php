<?php
use Symfony\Component\HttpFoundation\Request;
use Silex\Provider\FormServiceProvider;

use Soundify\Domain\Product;
use Soundify\Form\Type\ProductType;

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

// Add a new product
$app->match('/admin/product/add', function(Request $request) use ($app) {
    $product = new Product();
    $categories = $app['dao.category']->findAll();
    $productForm = $app['form.factory']->create(new ProductType($categories), $product);
    $productForm->handleRequest($request);
    if ($productForm->isSubmitted() && $productForm->isValid()) {
        $app['dao.product']->save($product);
        $app['session']->getFlashBag()->add('success', 'The product was successfully created.');
    }
    return $app['twig']->render('product_form.html.twig', array(
        'title' => 'Nouveau Produit',
        'productForm' => $productForm->createView()));
})->bind('admin_product_add');

// Edit an existing product
$app->match('/admin/product/{id}/edit', function($id, Request $request) use ($app) {
    $product = $app['dao.product']->find($id);
    $categories = $app['dao.category']->findAll();
    $productForm = $app['form.factory']->create(new ProductType($categories), $product);
    $productForm->handleRequest($request);
    if ($productForm->isSubmitted() && $productForm->isValid()) {
        $app['dao.product']->save($product);
        $app['session']->getFlashBag()->add('success', 'The product was succesfully updated.');
    }
    return $app['twig']->render('product_form.html.twig', array(
        'title' => 'Edit product',
        'productForm' => $productForm->createView()));
})->bind('admin_product_edit');

// Remove an product
$app->get('/admin/product/{id}/delete', function($id, Request $request) use ($app) {
    // Delete the product
    $app['dao.product']->delete($id);
    $app['session']->getFlashBag()->add('success', 'The product was succesfully removed.');
    // Redirect to admin home page
    return $app->redirect($app['url_generator']->generate('admin'));
})->bind('admin_product_delete');