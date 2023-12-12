<?php

namespace App\Controller;

use Core\View\View;
use App\AppRepoManager;
use Core\Form\FormError;
use Core\Form\FormResult;
use Core\Session\Session;
use Core\Controller\Controller;
use App\Controller\AuthController;

class AdminController extends Controller
{
    public function home()
    {

        // on vérifie que l'user est admin on verifie que l'user est cnnecté
        if (!AuthController::isAuth() || !AuthController::isAdmin()) self::redirect('/');

        $view = new View('admin/home');

        $view->render();
    }

    // liste des clients
    public function listUSer()
    {
        // on vérifie que l'user est admin on verifie que l'user est cnnecté
        if (!AuthController::isAuth() || !AuthController::isAdmin()) self::redirect('/');

        $data_view = [
            'users' => AppRepoManager::getRm()->getUserRepository()->getAllClientsActif(),
            'form_result' => Session::get(Session::FORM_RESULT)
        ];

        $view = new View('admin/list-user');

        $view->render($data_view);
    }

    // liste des membres de l'équipe
    public function listTeam()
    {
        // on vérifie que l'user est admin on verifie que l'user est cnnecté
        if (!AuthController::isAuth() || !AuthController::isAdmin()) self::redirect('/');

        $data_view = [
            'users' => AppRepoManager::getRm()->getUserRepository()->getAllTeamActif(),
            'form_result' => Session::get(Session::FORM_RESULT)
        ];

        $view = new View('admin/list-team');

        $view->render($data_view);
    }

    // liste des pizzas
    public function listPizza()
    {
        // on vérifie que l'user est admin on verifie que l'user est cnnecté
        if (!AuthController::isAuth() || !AuthController::isAdmin()) self::redirect('/');

        $view = new View('admin/list-pizza');

        $view->render();
    }

    // liste des commandes
    public function listOrder()
    {
        // on vérifie que l'user est admin on verifie que l'user est cnnecté
        if (!AuthController::isAuth() || !AuthController::isAdmin()) self::redirect('/');

        $view = new View('admin/list-order');

        $view->render();
    }

    //on désactive un user
    public function deleteUser(int $id)
    {
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
            self::redirect('/admin/user/list');
        }
        // si tout est  ok on redirife vers la liste des user
        self::redirect('/admin/user/list');
    }
}
