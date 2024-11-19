<?php 

namespace App\Controller;

use Core\View\View;
use Core\Session\Session;

class OrderController
{ 
    public function order()
    {
        $view = new View('user/user-order');

        $view_data = [
            //permet de recupérer les message d'erreurs du formulaire (s'il y en a)
            'form_result' => Session::get(Session::FORM_RESULT)
        ];

        $view->render($view_data);
    }


}