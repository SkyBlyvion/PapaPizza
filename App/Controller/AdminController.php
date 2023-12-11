<?php

namespace App\Controller;

use Core\View\View;
use Core\Controller\Controller;
use App\Controller\AuthController;

class AdminController extends Controller
{
    public function home()
    {
        // on verifie que l'user est cnnecté
        // on vérifie que l'user est admin
        if( !AuthController::isAuth() || !AuthController::isAdmin() ) self::redirect('/');

        $view = new View('admin/home');

        $view->render();
    }
}  