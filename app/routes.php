<?php
/*
$app->get('/', function($request, $response){
    return $this->view->render($response, 'home.twig');
});
*/

$app->get('/','HomeController:index');