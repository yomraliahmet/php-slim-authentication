<?php
namespace App\Controllers\Auth;

use App\Controllers\Controller;
use App\Models\User;

class AuthController extends Controller
{
    public function getSignUp($request, $response)
    {
        return $this->view->render($response, 'auth/signup.twig');
    }

    public function postSignUp($request, $response)
    {
        $user = new User;
        $user->email = $request->getParam('email');
        $user->name = $request->getParam('name');
        $user->password = password_hash($request->getParam('password'), PASSWORD_DEFAULT);

        if($user->save()){
            return $response->withRedirect($this->router->pathFor('home'));
        }

    }
}