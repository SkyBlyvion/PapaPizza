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

    // methode qui verifie que l'email est valide
    public function validEmail(string $email): bool
    {
        return filter_var($email, FILTER_VALIDATE_EMAIL);
    }

    //methode qui verifie que le mot de passe est valide
    // au moins 8 caractéres, une majuscule, une minuscule, un chiffre
    public function validPassword(string $password): bool
    {
        return preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.{8,})/', $password);
    }

    //méthode qui verifie si l'utilisateur est existe en base de données
    public function userExist(string $email): bool
    {
        $user = AppRepoManager::getRm()->getUserRepository()->findUserByEmail($email);
        return !is_null($user);
    }

    //methode qui formate les inputs du formulaire
    // cette méthode formate a la chaine sur la même string et reprend le value du dessus car il garde son etat d'avant et repasse en parametre
    public function validInput(string $value)
    {
        // on supprime les espaces en début et fin de string
        $value = trim($value);
        // on supprrime les balises html
        $value = strip_tags($value);
        // on supprime les antislash
        $value = stripslashes($value);
        // on supprime les caractéres speciaux
        $value = htmlspecialchars($value);

        return $value;
    }

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
        //on récupère les données du formulaire sous forme de tableau associatif
        $post_data = $request->getParsedBody();
        //on instancie notre class FormResult (elle s'occupe de stocker les messages d'erreur dans la session)
        $form_result = new FormResult();
        //on instancie un User
        $user = new User();

        //on vérifie que les champs soient remplis
        if (empty($post_data['email']) || empty($post_data['password'])) {
            $form_result->addError(new FormError('Veuillez renseigner tous les champs'));
        } else {
            $email = strtolower($this->validInput($post_data['email']));
            //on va verifier que l'email existe en bdd
            $user = AppRepoManager::getRm()->getUserRepository()->findUserByEmail($email);

            //si on a un retour négatif
            if (is_null($user)) {
                $form_result->addError(new FormError('Email et/ou mot de passe incorrect'));
            } else {
                //si on a un retour positif on verifie que le mot de passe est correct
                if (!password_verify($this->validInput($post_data['password']), $user->password)) {
                    $form_result->addError(new FormError('Email et/ou mot de passe incorrect'));
                }
            }
        }
        //si on a des erreurs on les stock en session et on renvoie vers la page de connexion
        if ($form_result->hasErrors()) {
            Session::set(Session::FORM_RESULT, $form_result);
            self::redirect('/connexion');
        }

        //si tout est OK on stock l'utilisateur en session et on le redirige vers la page d'accueil
        $user->password = '';
        Session::set(Session::USER, $user);
        Session::remove(Session::FORM_RESULT);
        self::redirect('/');
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

    // methode qui receptionne les données du formulaire d'inscription
    public function register(ServerRequest $request)
    {
        $data_form = $request->getParsedBody();
        $form_result = new FormResult();
        $user = new User();
        // var_dump($data_form); die;

        // on verifie que tous les champs sont remplis
        if (
            empty($data_form['email']) ||
            empty($data_form['password']) ||
            empty($data_form['password_confirm']) ||
            empty($data_form['lastname']) ||
            empty($data_form['firstname']) ||
            empty($data_form['phone'])
        ) {
            $form_result->addError(new FormError('Veuillez renseigner tous les champs'));
            // on verifire que les mots de passe correspondent
        } else if ($data_form['password'] !== $data_form['password_confirm']) {
            // on verifie que l'email est valide
            $form_result->addError(new FormError('Les mots de passe ne correspondent pas'));
            // on verifie que le mot de passe est valide
        } else if (!$this->validEmail($data_form['email'])) {
            $form_result->addError(new FormError('L\'email n\'est pas valide'));
        } else if (!$this->validPassword($data_form['password'])) {
            $form_result->addError(new FormError('Le mot de passe doit contenir au moins 8 caractères, une majuscule, une minuscule, un chiffre'));
            // on verifie que l'email n'est pas deja utilisé
        } else if ($this->userExist($data_form['email'])) {
            $form_result->addError(new FormError('L\'email existe déja'));
        } else {
            // on peut enregistrer notre nouvel user
            $data_user = [
                'email' => strtolower($this->validInput($data_form['email'])),
                'password' => password_hash($this->validInput($data_form['password']), PASSWORD_BCRYPT),
                'lastname' => $this->validInput($data_form['lastname']),
                'firstname' => $this->validInput($data_form['firstname']),
                'phone' => $this->validInput($data_form['phone'])
            ];

            $user = AppRepoManager::getRm()->getUserRepository()->addUser($data_user);
        }

        // s'il y a des erreurs, on les stocke en session et on redirige vers la page de formualire d'inscription
        if ($form_result->hasErrors()) {
            Session::set(Session::FORM_RESULT, $form_result);
            self::redirect('/inscription');
        }

        // si tout est ok, on enregistre l'user en session et on redirige vers la page d'accueil
        // on oublie pas de supprimer le mot de passe qui est hash
        $user->password = '';
        var_dump($user);
        Session::set(Session::USER, $user);
        Session::remove(Session::FORM_RESULT);
        //on redirige veres la page d'accueil
        self::redirect('/');
    }

    // methode qui receptionne les données du formulaire d'inscription
    public function registerTeam(ServerRequest $request)
    {
        $data_form = $request->getParsedBody();
        $form_result = new FormResult();
        $user = new User();
        // var_dump($data_form);

        // on verifie que tous les champs sont remplis
        if (
            empty($data_form['email']) ||
            empty($data_form['password']) ||
            empty($data_form['password_confirm']) ||
            empty($data_form['lastname']) ||
            empty($data_form['firstname']) ||
            empty($data_form['phone'])
        ) {
            $form_result->addError(new FormError('Veuillez renseigner tous les champs'));
            // on verifire que les mots de passe correspondent
        } else if ($data_form['password'] !== $data_form['password_confirm']) {
            // on verifie que l'email est valide
            $form_result->addError(new FormError('Les mots de passe ne correspondent pas'));
            // on verifie que le mot de passe est valide
        } else if (!$this->validEmail($data_form['email'])) {
            $form_result->addError(new FormError('L\'email n\'est pas valide'));
        } else if (!$this->validPassword($data_form['password'])) {
            $form_result->addError(new FormError('Le mot de passe doit contenir au moins 8 caractères, une majuscule, une minuscule, un chiffre'));
            // on verifie que l'email n'est pas deja utilisé
        } else if ($this->userExist($data_form['email'])) {
            $form_result->addError(new FormError('L\'email existe déja'));
        } else {
            // on peut enregistrer notre nouvel user
            $data_user = [
                'email' => strtolower($this->validInput($data_form['email'])),
                'password' => password_hash($this->validInput($data_form['password']), PASSWORD_BCRYPT),
                'lastname' => $this->validInput($data_form['lastname']),
                'firstname' => $this->validInput($data_form['firstname']),
                'phone' => $this->validInput($data_form['phone'])
            ];

            $user = AppRepoManager::getRm()->getUserRepository()->addTeam($data_user);
        }

        // s'il y a des erreurs, on les stocke en session et on redirige vers la page de formualire d'inscription
        if ($form_result->hasErrors()) {
            Session::set(Session::FORM_RESULT, $form_result);
            self::redirect('/admin/team/add');
        }


        //on redirige vers la liste des memnres d'equipe
        Session::remove(Session::FORM_RESULT);
        self::redirect('/admin/team/list');
    }

    //méthode qui receptionne les données du formulaire de modification de profil
    // methode qui receptionne les données du formulaire d'inscription
    public function updatedUser(ServerRequest $request)
    {
        $data_form = $request->getParsedBody();
        $form_result = new FormResult();
        $user = new User();
        // var_dump($data_form);

        // on verifie que tous les champs sont remplis
        if (
            empty($data_form['email']) ||
            empty($data_form['password']) ||
            empty($data_form['password_confirm']) ||
            empty($data_form['lastname']) ||
            empty($data_form['firstname']) ||
            empty($data_form['phone'])
        ) {
            $form_result->addError(new FormError('Veuillez renseigner tous les champs'));
            // on verifire que les mots de passe correspondent
        } else if ($data_form['password'] !== $data_form['password_confirm']) {
            // on verifie que l'email est valide
            $form_result->addError(new FormError('Les mots de passe ne correspondent pas'));
            // on verifie que le mot de passe est valide
        } else if (!$this->validEmail($data_form['email'])) {
            $form_result->addError(new FormError('L\'email n\'est pas valide'));
        } else if (!$this->validPassword($data_form['password'])) {
            $form_result->addError(new FormError('Le mot de passe doit contenir au moins 8 caractères, une majuscule, une minuscule, un chiffre'));
        } else {
            // on peut enregistrer nos modifications
            $data_user = [
                'email' => strtolower($this->validInput($data_form['email'])),
                'password' => password_hash($this->validInput($data_form['password']), PASSWORD_BCRYPT),
                'lastname' => $this->validInput($data_form['lastname']),
                'firstname' => $this->validInput($data_form['firstname']),
                'phone' => $this->validInput($data_form['phone'])
            ];

            $user = AppRepoManager::getRm()->getUserRepository()->addTeam($data_user);
        }

        // s'il y a des erreurs, on les stocke en session et on redirige vers la page de modification
        // if ($form_result->hasErrors()) {
        //     Session::set(Session::FORM_RESULT, $form_result);
        //     self::redirect('/user/update/user');
        // }


        //on redirige vers le compte user
        self::redirect('/user/account');
    }

    //méthode qui permet de vérifier si un user est connecté
    public static function isAuth(): bool
    {
        return !is_null(Session::get(Session::USER));
    }

    //méthode qui verifie si l'user est administrateur
    public static function isAdmin(): bool
    {
        return Session::get(Session::USER)->is_admin;
    }

    //méthode pour se déconnecter
    public function logout()
    {
        //on détruit la Session
        Session::remove(Session::USER);
        // on redirige vers la page
        self::redirect('/');
    }
}
