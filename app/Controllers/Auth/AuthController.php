<?php
namespace App\Controllers\Auth;

use App\Controllers\Controller;
use App\Models\User;
use Respect\Validation\Validator as v;

class AuthController extends Controller
{

    public function getSignOut($request, $response)
    {
        $this->auth->logout();

        return $response->withRedirect($this->router->pathFor('home'));
    }

    public function getSignIn($request, $response)
    {
        return $this->view->render($response, 'auth/signin.twig');
    }

    public function postSignIn($request, $response)
    {
        $auth = $this->auth->attempt(
            $request->getParam('email'),
            $request->getParam('password')
        );

        if(!$auth){
            $this->flash->addMessage('error', 'Kullanıcı bulunamadı!.');
            return $response->withRedirect($this->router->pathFor('auth.signin'));
        }

        return $response->withRedirect($this->router->pathFor('home'));

    }




    public function getSignUp($request, $response)
    {
        return $this->view->render($response, 'auth/signup.twig');
    }

    public function postSignUp($request, $response)
    {

        $validation = $this->validator->validate($request, [
            'email' => v::noWhitespace()->notEmpty()->email()->emailAvailable(),
            'name' => v::noWhitespace()->notEmpty()->alpha(),
            'password' => v::noWhitespace()->notEmpty(),
        ]);

        if($validation->failed()){
            return $response->withRedirect($this->router->pathFor('auth.signup'));
        }

        $user = new User;
        $user->email = $request->getParam('email');
        $user->name = $request->getParam('name');
        $user->password = password_hash($request->getParam('password'), PASSWORD_DEFAULT);

        if($user->save()){

            $this->flash->addMessage('info', 'Başarıyla kaydoldunuz');

            $this->auth->attempt($user->email, $request->getParam('password'));

            return $response->withRedirect($this->router->pathFor('home'));
        }

    }
}