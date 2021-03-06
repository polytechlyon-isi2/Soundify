<?php


use Symfony\Component\Debug\ErrorHandler;
use Symfony\Component\Debug\ExceptionHandler;

// Register global error and exception handlers
ErrorHandler::register();
ExceptionHandler::register();

// Register service providers

$app->register(new Silex\Provider\DoctrineServiceProvider());
$app->register(new Silex\Provider\TwigServiceProvider(), array(
    'twig.path' => __DIR__.'/../views',
));
$app['twig'] = $app->share($app->extend('twig', function(Twig_Environment $twig, $app) {
    $twig->addExtension(new Twig_Extensions_Extension_Text());
    return $twig;
}));
$app->register(new Silex\Provider\ValidatorServiceProvider());
$app->register(new Silex\Provider\UrlGeneratorServiceProvider());
$app->register(new Silex\Provider\SessionServiceProvider());
$app->register(new Silex\Provider\SecurityServiceProvider(), array(
    'security.firewalls' => array(
        'secured' => array(
            'pattern' => '^/',
            'anonymous' => true,
            'logout' => true,
            'form' => array('login_path' => '/login', 'check_path' => '/login_check'),
            'users' => $app->share(function () use ($app) {
                return new Soundify\DAO\UserDAO($app['db']);
            }),
        ),
    ),
    'security.role_hierarchy' => array(
        'ROLE_ADMIN' => array('ROLE_USER'),
    ),
    'security.access_rules' => array(
        array('^/admin', 'ROLE_ADMIN'),
        array('^/cart', 'ROLE_USER')
    ),
));

$app->register(new Silex\Provider\FormServiceProvider());
$app->register(new Silex\Provider\TranslationServiceProvider());

$app['dao.user'] = $app->share(function ($app) {
    return new Soundify\DAO\UserDAO($app['db']);
});

$app['dao.category'] = $app->share(function ($app) {
    $categoryDAO = new Soundify\DAO\CategoryDAO($app['db']);
   // $categoryDAO->setProductDAO($app['dao.product']);
    return $categoryDAO;
});

$app['dao.product'] = $app->share(function ($app) {
    $productDAO = new Soundify\DAO\ProductDAO($app['db']);
    $productDAO->setCategoryDAO($app['dao.category']);
   // $productDAO->setCartDAO($app['dao.cart']);
    return $productDAO;
});

$app['dao.cart'] = $app->share(function ($app) {
    $cartDAO = new Soundify\DAO\CartDAO($app['db']);
    $cartDAO->setProductDAO($app['dao.product']);
    $cartDAO->setUserDAO($app['dao.user']);
    return $cartDAO;
});

$app['twig'] = $app->share($app->extend('twig', function($twig, $app) {
    $twig->addFunction(new \Twig_SimpleFunction('asset', function ($asset) use ($app) {
        return sprintf('%s/%s',
                       $app['request']->getBasePath(),
                       ltrim($asset, '/')
                      );
    }));

    return $twig;
}));