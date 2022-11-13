<?php

use Illuminate\Routing\Router;

Admin::routes();

Route::group([
    'prefix'        => config('admin.route.prefix'),
    'namespace'     => config('admin.route.namespace'),
    'middleware'    => config('admin.route.middleware'),
    'as'            => config('admin.route.prefix') . '.',
], function (Router $router) {

    $router->get('/', 'HomeController@index')->name('home');

    $router->resource('articles/', ArticleController::class);
    $router->resource('article-types', ArticleTypeController::class);
    $router->resource('pics', PicController::class);
    $router->resource('fontsize', ExampleController::class);
    $router->resource('modal', ModalController::class);
    // modal/customersみたいなの× いちいちルート名変えないと/admin/customers/adminみたいになる
    $router->get('customers', 'ModalController@modal');
    $router->get('search/{id}', 'ModalController@getCustomer');
});
// Route::get('modal/customers', 'App\Admin\Controllers\ModalController@modal');
// Route::get('modal/customers', [ModalController::class, 'modal']);
// Route::get('modal/customers', 'ModalController@modal');
