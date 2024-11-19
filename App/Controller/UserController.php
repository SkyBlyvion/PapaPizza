<?php

namespace App\Controller;

use App\Model\User;
use Core\View\View;
use App\AppRepoManager;
use Core\Form\FormError;
use Core\Form\FormResult;
use Core\Session\Session;
use Core\Controller\Controller;
use Laminas\Diactoros\ServerRequest;
use App\Controller\AuthController;

class UserController extends Controller
{
    public function account()
    {
        $view = new View('user/account');

        $view->render();
    }

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

    //methode qui retourne sur le formulaire de modification de profil
    public function updateUser($id)
    {
        $view = new View('user/update-user');

        $view_data = [
            'form_result' => Session::get(Session::FORM_RESULT),
            'user_id ' => $id ?? -1
        ];

        $view->render($view_data);
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
            // empty($data_form['password']) ||
            // empty($data_form['password_confirm']) ||
            empty($data_form['lastname']) ||
            empty($data_form['firstname']) ||
            empty($data_form['phone'])
        ) {
            $form_result->addError(new FormError('Veuillez renseigner tous les champs'));
            // var_dump($form_result);
            // on verifie que l'email est valide
        } else if (!$this->validEmail($data_form['email'])) {
            $form_result->addError(new FormError('L\'email n\'est pas valide'));
            // var_dump($form_result);
        } else {
            // on peut enregistrer nos modifications
            $data_user = [
                'email' => strtolower($this->validInput($data_form['email'])),
                'lastname' => $this->validInput($data_form['lastname']),
                'firstname' => $this->validInput($data_form['firstname']),
                'phone' => $this->validInput($data_form['phone']),
                'id' => $data_form['id']
            ];
            $user = AppRepoManager::getRm()->getUserRepository()->updateUser($data_user);
        }

        //on redirige vers le compte user
        self::redirect('/account/' . $data_form['id']);
    }

    //on désactive un user par un user
    public function deleteUser(int $id)
    {
        // on vérifie que l'user est cnnecté
        if (!AuthController::isAuth()) self::redirect('/');

        $form_result = new FormResult();
        // on apelle la méthode qui désactive un user. vient de UserRepository.php
        $deleteUser = AppRepoManager::getRm()->getUserRepository()->deleteUser($id);
        // si la méthode renvoie false on stock un message d'erreur
        if (!$deleteUser) {
            $form_result->addError(new FormError('Erreur lors de la suppression de l\'utilisateur'));
        }

        // si il y a des erreurs on les enregistre en session
        if ($form_result->hasErrors()) {
            Session::set(Session::FORM_RESULT, $form_result);
            self::redirect('/user/account/{id}');
        }
        // si tout est  ok on redirige vers l'accueil et on unlog
        Session::remove(Session::FORM_RESULT);
        Session::remove(Session::USER);
        self::redirect('/');
       
    }
}
