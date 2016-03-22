<?php
use Symfony\Component\HttpFoundation\Request;
use Silex\Provider\FormServiceProvider;

use Soundify\Domain\Product;
use Soundify\Form\Type\ProductType;
use Soundify\Domain\Category;
use Soundify\Form\Type\CategoryType;
use Soundify\Domain\User;
use Soundify\Form\Type\UserType;
use Soundify\Domain\Cart;

// Home page
$app->get('/', function (Request $request) use ($app) {
    $categories = $app['dao.category']->findAll();
    return $app['twig']->render('index.html.twig', array(
        'error'         => $app['security.last_error']($request),
        'last_username' => $app['session']->get('_security.last_username'),
        'categories' => $categories,
    ));
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

// Product page
$app->get('/product/{id}', function ($id) use ($app) {
    $categories = $app['dao.category']->findAll();
    $product = $app['dao.product']->find($id);
    return $app['twig']->render('product.html.twig', array('categories' => $categories, 'product' => $product));
})->bind('product');

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
        $app['session']->getFlashBag()->add('success', 'Le produit "'. $product->getName() . '" a bien été créé.');
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
        $app['session']->getFlashBag()->add('success', 'Le produit "'. $product->getName() . '" a bien été mis à jour.');
    }
    return $app['twig']->render('product_form.html.twig', array(
        'title' => 'Edition du produit',
        'productForm' => $productForm->createView()));
})->bind('admin_product_edit');

// Remove an product
$app->get('/admin/product/{id}/delete', function($id, Request $request) use ($app) {
    // Delete the product
    $app['dao.product']->delete($id);
    $app['session']->getFlashBag()->add('success', 'Le produit a bien été supprimé.');
    // Redirect to admin home page
    return $app->redirect($app['url_generator']->generate('admin'));
})->bind('admin_product_delete');

// Add a new category
$app->match('/admin/category/add', function(Request $request) use ($app) {
    $category = new Category();
    $categoryForm = $app['form.factory']->create(new CategoryType(), $category);
    $categoryForm->handleRequest($request);
    if ($categoryForm->isSubmitted() && $categoryForm->isValid()) {
        $app['dao.category']->save($category);
        $app['session']->getFlashBag()->add('success', 'La catégorie "'. $category->getName() . '" a bien été créée.');
    }
    return $app['twig']->render('category_form.html.twig', array(
        'title' => 'Nouvelle catégorie',
        'categoryForm' => $categoryForm->createView()));
})->bind('admin_category_add');

// Edit an existing product
$app->match('/admin/category/{id}/edit', function($id, Request $request) use ($app) {
    $category = $app['dao.category']->find($id);
    $categoryForm = $app['form.factory']->create(new CategoryType(), $category);
    $categoryForm->handleRequest($request);
    if ($categoryForm->isSubmitted() && $categoryForm->isValid()) {
        $app['dao.category']->save($category);
        $app['session']->getFlashBag()->add('success', 'La catégorie "'. $category->getName() . '" a bien été mise à jour.');
    }
    return $app['twig']->render('category_form.html.twig', array(
        'title' => 'Edition de la catégorie',
        'categoryForm' => $categoryForm->createView()));
})->bind('admin_category_edit');

// Remove an product
$app->get('/admin/category/{id}/delete', function($id, Request $request) use ($app) {
    // Delete the category
    $app['dao.category']->delete($id);
    $app['session']->getFlashBag()->add('success', 'La catégorie a bien été supprimée.');
    // Redirect to admin home page
    return $app->redirect($app['url_generator']->generate('admin'));
})->bind('admin_category_delete');

// Add a user
$app->match('/admin/user/add', function(Request $request) use ($app) {
    $user = new User();
    $userForm = $app['form.factory']->create(new UserType(), $user);
    $userForm->handleRequest($request);
    if ($userForm->isSubmitted() && $userForm->isValid()) {
        // generate a random salt value
        $salt = substr(md5(time()), 0, 23);
        $user->setSalt($salt);
        $plainPassword = $user->getPassword();
        // find the default encoder
        $encoder = $app['security.encoder.digest'];
        // compute the encoded password
        $password = $encoder->encodePassword($plainPassword, $user->getSalt());
        $user->setPassword($password); 
        $app['dao.user']->save($user);
        $app['session']->getFlashBag()->add('success', 'L\'utilisateur "'. $user->getName() . " " . $user->getFirstname() . '" a bien été créé.');
    }
    return $app['twig']->render('user_form.html.twig', array(
        'title' => 'Nouvel utilisateur',
        'userForm' => $userForm->createView()));
})->bind('admin_user_add');

// Edit an existing user
$app->match('/admin/user/{id}/edit', function($id, Request $request) use ($app) {
    $user = $app['dao.user']->find($id);
    $userForm = $app['form.factory']->create(new UserType(), $user);
    $userForm->handleRequest($request);
    if ($userForm->isSubmitted() && $userForm->isValid()) {
        $plainPassword = $user->getPassword();
        // find the encoder for the user
        $encoder = $app['security.encoder_factory']->getEncoder($user);
        // compute the encoded password
        $password = $encoder->encodePassword($plainPassword, $user->getSalt());
        $user->setPassword($password); 
        $app['dao.user']->save($user);
        $app['session']->getFlashBag()->add('success', 'L\'utilisateur '. $user->getName() . " " . $user->getFirstname() . '" a bien été mis à jour.');
    }
    return $app['twig']->render('user_form.html.twig', array(
        'title' => 'Edition de l\'utilisateur',
        'userForm' => $userForm->createView()));
})->bind('admin_user_edit');

// Remove a user
$app->get('/admin/user/{id}/delete', function($id, Request $request) use ($app) {
    // Delete all associated comments
    $app['dao.comment']->deleteAllByUser($id);
    // Delete the user
    $app['dao.user']->delete($id);
    $app['session']->getFlashBag()->add('success', 'L\'utilisateur a bien été supprimé.');
    // Redirect to admin home page
    return $app->redirect($app['url_generator']->generate('admin'));
})->bind('admin_user_delete');

// New user
$app->match('/signup', function(Request $request) use ($app) {
    $user = new User();
    $userForm = $app['form.factory']->create(new UserType(), $user);
    $userForm->handleRequest($request);
    if ($userForm->isSubmitted() && $userForm->isValid()) {
        // generate a random salt value
        $salt = substr(md5(time()), 0, 23);
        $user->setSalt($salt);
        $plainPassword = $user->getPassword();
        // find the default encoder
        $encoder = $app['security.encoder.digest'];
        // compute the encoded password
        $password = $encoder->encodePassword($plainPassword, $user->getSalt());
        $user->setPassword($password); 
        $app['dao.user']->save($user);
        $app['session']->getFlashBag()->add('success', 'L\'utilisateur "'. $user->getName() . " " . $user->getFirstname() . '" a bien été créé.');
    }
    return $app['twig']->render('user_form.html.twig', array(
        'title' => 'Inscription',
        'userForm' => $userForm->createView()));
})->bind('sign_up');

// New user
$app->match('/myaccount', function(Request $request) use ($app) {
    $user = $app['dao.user']->find($app['user']->getId());
    $userForm = $app['form.factory']->create(new UserType(), $user);
    $userForm->handleRequest($request);
    if ($userForm->isSubmitted() && $userForm->isValid()) {
        $plainPassword = $user->getPassword();
        // find the encoder for the user
        $encoder = $app['security.encoder_factory']->getEncoder($user);
        // compute the encoded password
        $password = $encoder->encodePassword($plainPassword, $user->getSalt());
        $user->setPassword($password); 
        $app['dao.user']->save($user);
        $app['session']->getFlashBag()->add('success', 'Votre compte a bien été mis à jour.');
    }
    return $app['twig']->render('user_form.html.twig', array(
        'title' => 'Modifier mon compte',
        'userForm' => $userForm->createView()));
})->bind('myaccount');


// Cart page
$app->get('/cart', function() use ($app) {
    $cart = $app['dao.cart']->findAllByCategory($app['user']->getId());
    return $app['twig']->render('cart.html.twig', array(
        'cart' => $cart ));
})->bind('cart');