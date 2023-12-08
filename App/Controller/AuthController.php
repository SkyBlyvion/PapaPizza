<?php

namespace App\Controller;

use App\AppRepoManager;
use App\Model\User;
use Core\View\View;
use Core\Form\FormError;
use Core\Form\FormResult;
use Core\Session\Session;
use Core\Controller\Controller;
use Laminas\Diactoros\ServerRequest;

class AuthController extends Controller
{
    public const AUTH_SALT = 'c56a7523d96942a834b9cdc249bd4e8c7aa9';
    public const AUTH_PEPPER = '8d746680fd4d7cbac57fa9f033115fc52196';
    // c56a7523d96942a834b9cdc249bd4e8c7aa9toto8d746680fd4d7cbac57fa9f033115fc52196
    //méthode qui renvoi sur la vue du formulaire de connexion
    public function loginForm()
    {
        $view = new View('auth/login');

        $view_data = [
            //permet de recupérer les message d'erreurs du formulaire (s'il y en a)
            'form_result' => Session::get(Session::FORM_RESULT)
        ];

        $view->render($view_data);
    }
    //méthode qui receptionne les données du formulaire de connexion
    public function login(ServerRequest $request)
    {
        // //on récupère les données du formulaire sous forme de tableau associatif
        // $post_data = $request->getParsedBody();
        // //on instancie notre class FormResult (elle s'occupe de stocker les messages d'erreur dans la session)
        // $form_result = new FormResult();
        // //on instancie un User
        // $user = new User();

        // //on vérifie que les champs soient remplis
        // if(empty( $post_data['email']) || empty( $post_data['password'])){
        //     $form_result->addError(new FormError('Veuillez renseigner tous les champs'));
        // }else{
        //     //on va vérifier que l'email existe en bdd
        //     $email = $post_data['email'];
        //     $password = self::hash($post_data['password']);

        //    //appel au repository pour vérifier si l'utilisateur existe
        //    $user = AppRepoManager::getRm()->getUserRepository()->checkAuth($email, $password);

        //    //si on a un retour négatif
        //    if(is_null($user)){
        //        $form_result->addError(new FormError('Email et/ou mot de passe incorrect'));
        //    }
        // }
        // //si on a des erreurson les stock en session et on renvoie vers la page de connexion
        // if($form_result->hasErrors()){
        //     Session::set(Session::FORM_RESULT, $form_result);
        //     self::redirect('/connexion');
        // }

        // //si tout est OK on stock l'utilisateur en session et on le redirige vers la page d'accueil
        // $user->password = '';
        // Session::set(Session::USER, $user);
        // self::redirect('/');

        // //méthode qui hash le mot de passe
        // public static function hash(string $password): string
        // {
        //     //on ajoute SEL + mot de passe + POIVRE
        //     $data = self::AUTH_SALT . $password . self::AUTH_PEPPER;
        //     return hash('sha512', $data);
        // } 

        // //méthode qui permet de savoir si un utilisateur est connecté
        // public static function isAuth():bool 
        // {
        //     return !is_null(Session::get(Session::USER));
    }

    //méthode qui renvoi sur la vue du formulaire d'inscription
    public function registerForm()
    {
        $view = new View('auth/register');

        $view_data = [
            //permet de recupérer les message d'erreurs du formulaire (s'il y en a)
            'form_result' => Session::get(Session::FORM_RESULT)
        ];

        $view->render($view_data);
    }
}
