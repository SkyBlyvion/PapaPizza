<?php

namespace App\Controller;

use Core\View\View;
use Core\Controller\Controller;

class AdminController extends Controller
{
    public function home()
    {
        $view = new View('admin/home');

        $view->render();
    }
}  