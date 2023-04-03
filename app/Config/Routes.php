<?php

namespace Config;

// Create a new instance of our RouteCollection class.
$routes = Services::routes();

// Load the system's routing file first, so that the app and ENVIRONMENT
// can override as needed.
if (is_file(SYSTEMPATH . 'Config/Routes.php')) {
    require SYSTEMPATH . 'Config/Routes.php';
}

/*
 * --------------------------------------------------------------------
 * Router Setup
 * --------------------------------------------------------------------
 */
$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('Home');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
$routes->set404Override();
$routes->setAutoRoute(true);
// The Auto Routing (Legacy) is very dangerous. It is easy to create vulnerable apps
// where controller filters or CSRF protection are bypassed.
// If you don't want to define all routes, please use the Auto Routing (Improved).
// Set `$autoRoutesImproved` to true in `app/Config/Feature.php` and set the following to true.
// $routes->setAutoRoute(false);

/*
 * --------------------------------------------------------------------
 * Route Definitions
 * --------------------------------------------------------------------
 */

// We get a performance increase by specifying the default
// route since we don't have to scan directories.
$routes->get('/', 'Home::index');
// $routes->group('userbio',function ($routes){
//     $routes->get('/', 'Userbio::index');
//     $routes->get('view', 'Userbio::view');
//     $routes->get('delete', 'Userbio::delete');
//     $routes->get('(:any)', 'Userbio::form');
//     $routes->post('prosses', 'Userbio::prosses');
// });
$routes->group('alamatapi',function ($routes){
    $routes->match(['get','post'],'add', 'Api\AlamatKirimApi::Add');
    $routes->match(['get','post'],'edit', 'Api\AlamatKirimApi::Edit');
    $routes->match(['get','post'],'getone', 'Api\AlamatKirimApi::getOne');
});

$routes->group('cartapi',function ($routes){
    $routes->match(['get','post'],'/', 'Api\CartApi::Index');
    $routes->match(['get','post'],'addcart', 'Api\CartApi::addCart');
    $routes->match(['get','post'],'countcart', 'Api\CartApi::countCart');
    $routes->match(['get','post'],'hapuscart', 'Api\CartApi::hapuscart');
    $routes->match(['get','post'],'updateqty', 'Api\CartApi::updateQty');
    $routes->match(['get','post'],'getCart', 'Api\CartApi::getCart');
});

$routes->group('orderapi',function ($routes){
    $routes->match(['get','post'],'/', 'Api\OrderApi::Index');
    $routes->match(['get','post'],'getOngkir', 'Api\OrderApi::getOngkir');
    $routes->match(['get','post'],'input', 'Api\OrderApi::input');
    $routes->match(['get','post'],'getPemesanan', 'Api\OrderApi::getPemesanan');
    $routes->match(['get','post'],'getPemesananDetail', 'Api\OrderApi::getPemesananDetail');
    $routes->match(['get','post'],'hapusPesanan', 'Api\OrderApi::hapusPesanan');
    $routes->match(['get','post'],'upload', 'Api\OrderApi::upload');
    $routes->match(['get','post'],'updatePesananDiterima', 'Api\OrderApi::updatePesananDiterima');
    $routes->match(['get','post'],'getOrderSelesai', 'Api\OrderApi::getOrderSelesai');
    $routes->match(['get','post'],'kirimRating', 'Api\OrderApi::kirimRating');
});

$routes->group('produkapi',function ($routes){
    $routes->match(['get','post'],'/', 'Api\ProdukApi::Index');
    $routes->match(['get','post'],'kategori', 'Api\ProdukApi::kategori');
    $routes->match(['get','post'],'getRekening', 'Api\ProdukApi::getRekening');
});

$routes->group('userapi',function ($routes){
    $routes->match(['get','post'],'/', 'Api\UserApi::Index');
    $routes->match(['get','post'],'login', 'Api\UserApi::login');
    $routes->match(['get','post'],'getOneUser', 'Api\UserApi::getOneUser');
    $routes->match(['get','post'],'register', 'Api\UserApi::register');
    $routes->match(['get','post'],'upload', 'Api\UserApi::upload');
    $routes->match(['get','post'],'edit', 'Api\UserApi::edit');
});

$routes->get('api/userapi','Api\AlamatKirimApi::getOne');

/*
 * --------------------------------------------------------------------
 * Additional Routing
 * --------------------------------------------------------------------
 *
 * There will often be times that you need additional routing and you
 * need it to be able to override any defaults in this file. Environment
 * based routes is one such time. require() additional route files here
 * to make that happen.
 *
 * You will have access to the $routes object within that file without
 * needing to reload it.
 */
if (is_file(APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php')) {
    require APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php';
}
