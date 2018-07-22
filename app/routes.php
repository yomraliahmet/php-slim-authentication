<?php
/*
$app->get('/', function($request, $response){
    return $this->view->render($response, 'home.twig');
});
*/

use App\Middleware\AuthMiddleware;
use App\Middleware\GuestMiddleware;


$app->get('/','HomeController:index')->setName('home');

// Sadece ziyaretçilerin görebileceği sayfalar, giriş yapanlar da göremez.
$app->group('', function(){
    $this->get('/auth/signup','AuthController:getSignUp')->setName('auth.signup');
    $this->post('/auth/signup','AuthController:postSignUp');

    $this->get('/auth/signin','AuthController:getSignIn')->setName('auth.signin');
    $this->post('/auth/signin','AuthController:postSignIn');
})->add(new GuestMiddleware($container));



// sayfaların hepsi "auth" altında oluşturulduğu için 
// giriş yapıldıktan sonra ulaşılması gerekenleri ayırt etmek amacıyla middleware oluşturuldu.
$app->group('', function(){
    $this->get('/auth/signout','AuthController:getSignOut')->setName('auth.signout');
    $this->get('/auth/password/change','PasswordController:getChangePassword')->setName('auth.password.change');
    $this->post('/auth/password/change','PasswordController:postChangePassword');
})->add(new AuthMiddleware($container));


