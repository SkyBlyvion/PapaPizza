<?php

namespace App;

use App\Model\Pizza;
use MiladRahimi\PhpRouter\Router;
use App\Controller\AuthController;
use App\Controller\UserController;
use App\Controller\AdminController;
use App\Controller\OrderController;
use App\Controller\PizzaController;
use Core\Database\DatabaseConfigInterface;
use MiladRahimi\PhpRouter\Exceptions\RouteNotFoundException;
use MiladRahimi\PhpRouter\Exceptions\InvalidCallableException;

class App implements DatabaseConfigInterface
{
    //on déclare des constantes pour la connexion à la base de données
    private const DB_HOST = 'database';
    private const DB_NAME = 'papapizza';
    private const DB_USER = 'admin';
    private const DB_PASS = 'admin';

    //on déclare une propriété privée qui va contenir une intance de app
    //Design pattern Singleton
    private static ?self $instance = null;

    //méthode public appelé au démarrage de l'application dans index.php
    public static function getApp():self
    {
        if (is_null(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    //On va configurer notre router
    private Router $router;

    public function getRouter(): Router
    {
        return $this->router;
    }

    private function __construct()
    {
        $this->router = Router::create();
    }

    //on aura 3 méthodes à définir pour le router
    //1: méthode start() qui va démarrer le router dans l'application
    public function start(): void
    {
        //on ouvre l'accès à la session
        session_start();
        //on enregistre les routes
        $this->registerRoutes();
        //on démarre le router
        $this->startRouter();
    }

    //2: méthode qui enregistre les routes
    private function registerRoutes()
    {
        //exemple de routes avec un controller
        $this->router->get('/', [PizzaController::class, 'home']);
        $this->router->get('/pizzas', [PizzaController::class, 'getPizzas']);
        $this->router->get('/pizza/{id}', [PizzaController::class, 'getPizzaById']);


        // route pour le formulaire de login
        $this->router->get('/connexion', [AuthController::class, 'loginForm']);
        // route qui recoit le formulaire de login
        $this->router->post('/login', [AuthController::class, 'login']);

        // route pour le formulaire d'inscriptiobn
        $this->router->get('/inscription', [AuthController::class, 'registerForm']);
        // route qui recoit le formulaire d'inscriptiobn
        $this->router->post('/register', [AuthController::class, 'register']);

        // route pour se déconnecer
        $this->router->get('/logout', [AuthController::class, 'logout']);

        // route qui recevra les formulaires d'ajout d'un membre d'équipe
        $this->router->post('/register-team', [AuthController::class, 'registerTeam']);


        /* PARTIE UTILISATEUR */
        // route pour le formulaire de modification du profil
        $this->router->get('/user/update/user/{id}', [UserController::class, 'updateUser']);
        //route qui recevra le formumlaire de modification du profil
        $this->router->post('/updated-user', [UserController::class, 'updatedUser']);

        //route pour "supprimer" un user
        $this->router->get('/user/user/delete/{id}', [UserController::class, 'deleteUser']);
        
        // route pour acceder au compte user
        $this->router->get('/account/{id}', [UserController::class, 'account']);

        //route pour la création de pizza par user
        $this->router->get('/user/create-pizza/{id}', [PizzaController::class, 'createPizza']);
        // route qui recevra le formulaire de creation de pizza
        $this->router->post('/user/create-pizza', [PizzaController::class, 'createPizzaForm']);


        /* PARTIE ORDER*/
        // route pour acceder a la page de commandes de user
        $this->router->get('/user-order', [OrderController::class, 'order']);
        

        /* PARTIE BACK OFFICE */
        // route pour acceder a la page d'administration
        $this->router->get('/admin/home', [AdminController::class, 'home']);
        // route pour acceder a la liste des utilisateurs
        $this->router->get('/admin/user/list', [AdminController::class, 'listUser']);
        // route pour acceder a la liste des membres d'équipe
        $this->router->get('/admin/team/list', [AdminController::class, 'listTeam']);
        // route pour acceder a la liste des pizzas du backoffice
        $this->router->get('/admin/pizza/list', [AdminController::class, 'listPizza']);
        // route pour acceder a la liste des commandes
        $this->router->get('/admin/order/list', [AdminController::class, 'listOrder']);

        //route pour ajouter une pizza
        $this->router->get('/admin/pizza/add', [AdminController::class, 'addPizza']);
        //route qui receptionne le formulaire d'ajout d'une pizza
        $this->router->post('/add-pizza-form', [AdminController::class, 'addPizzaForm']);

        // route pour ajouter un membre d'équipe
        $this->router->get('/admin/team/add', [AdminController::class, 'addTeam']);
        //route pour "supprimer" un user
        $this->router->get('/admin/user/delete/{id}', [AdminController::class, 'deleteUserAdmin']);

        // route pour supprimer une pizza du backoffice
        $this->router->get('/admin/pizza/delete/{id}', [AdminController::class, 'deletePizza']);
        // route pour modifier une pizza du backoffice
        $this->router->get('/admin/pizza/update/{id}', [AdminController::class, 'updatePizza']);
        
        // route qui receptionne le formulaire de modification du nom d'une pizza
        $this->router->post('/update-pizza/name', [AdminController::class, 'updatePizzaName']);
        // route pour modification image
        $this->router->post('/update-pizza/image', [AdminController::class, 'updatePizzaImage']);
        // route pour modification ingredients pizza
        $this->router->post('/update-pizza/ingredients', [AdminController::class, 'updatePizzaIngredients']);
        //route pour modifier prix par taille
        $this->router->post('/update-pizza/price', [AdminController::class, 'updatePizzaPrice']);

        

  
    }

    //3: méthode qui va démarrer le router
    private function startRouter()
    {
        try {
            $this->router->dispatch();
        } catch (RouteNotFoundException $e) {
            //TODO : gérer la vue 404
            var_dump('Erreur 404: ' . $e);
        } catch (InvalidCallableException $e) {
            //TODO : gérer la vue 500
            var_dump('Erreur 500: ' . $e);
        }
    }

    //on déclare nos méthodes imposé par l'interface DatabaseConfigInterface
    public function getHost(): string
    {
        return self::DB_HOST;
    }

    public function getName(): string
    {
        return self::DB_NAME;
    }

    public function getUser(): string
    {
        return self::DB_USER;
    }

    public function getPass(): string
    {
        return self::DB_PASS;
    }
}
