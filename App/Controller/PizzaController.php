<?php

namespace App\Controller;

use Core\View\View;
use App\AppRepoManager;
use Core\Controller\Controller;
use App\Repository\PizzaRepository;

class PizzaController extends Controller
{
    public function home()
    {
        //on instancie la class View et on lui passe en paramètre le chemin de la vue
        // dossier/fichier
        $view = new View('home/index');

        //on appelle la méthode render() de la class View
        $view->render();
    }

    // méthode qui récupére la liste des pizzas
    public function getPizzas()
    {
        $view_data = [
            'pizzas' => AppRepoManager::getRm()->getPizzaRepository()->getAllPizzas()
        ];

        $view = new View('home/pizzas');

        $view->render($view_data);
    }

    //methode qui recupere une pizza par son id
    public function getPizzaById(int $id)
    {
        $view_data = [
            'pizza' => AppRepoManager::getRm()->getPizzaRepository()->getPizzaById($id)
        ];
        
        $view = new View('home/pizza_detail');

        $view->render($view_data);
        // $price = AppRepoManager::getRm()->getPriceRepository()->getPriceByPizzaId($id);
        // $ingredients = AppRepoManager::getRm()->getPizzaIngredientRepository()->getIngredientByPizzaId($id);
        // var_dump($price);
        // var_dump($ingredients);
    }
}
