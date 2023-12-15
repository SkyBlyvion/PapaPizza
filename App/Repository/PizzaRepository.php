<?php

namespace App\Repository;

use App\AppRepoManager;
use App\Model\Pizza;
use Core\Repository\Repository;

class PizzaRepository extends Repository
{
    public function getTableName(): string
    {
        return 'pizza';
    }

    //  on crée une méthode qui va récupérer toutes les pizzas
    public function getAllPizzas(): array
    {
        //on déclare un tableau vide
        $array_result = [];

        // on déclare la requete SQL
        $query = sprintf(
            'SELECT p.`id`, p.`name`, p.`image_path` 
            FROM %1$s AS p
            INNER JOIN %2$s AS u ON p.`user_id`=u.`id`
            WHERE p.`is_active`=1 
            AND u.`is_admin`=1',
            $this->getTableName(),
            AppRepoManager::getRm()->getUserRepository()->getTableName()
        );

        // on peut directement executer la requete avec la methode query()
        $stmt = $this->pdo->query($query);

        // on verifie si la requete a bien ete executée
        if (!$stmt) return $array_result;

        //on recupere les données de la table dans une boucle
        while ($row_data = $stmt->fetch()) {
            $array_result[] = new Pizza($row_data);
        }

        return $array_result;
    }

    //  on crée une méthode qui va récupérer toutes les pizzas
    public function getAllPizzasWithInfo(): array
    {
        //on déclare un tableau vide
        $array_result = [];

        // on déclare la requete SQL
        $query = sprintf(
            'SELECT p.`id`, p.`name`, p.`image_path` 
                FROM %1$s AS p
                INNER JOIN %2$s AS u ON p.`user_id`=u.`id`
                WHERE p.`is_active`=1 
                AND u.`is_admin`=0',
            $this->getTableName(),
            AppRepoManager::getRm()->getUserRepository()->getTableName()
        );

        // on peut directement executer la requete avec la methode query()
        $stmt = $this->pdo->query($query);

        // on verifie si la requete a bien ete executée
        if (!$stmt) return $array_result;

        //on recupere les données de la table dans une boucle
        while ($row_data = $stmt->fetch()) {
            $pizza = new Pizza($row_data);
            //on hydrate

            // on va hydrater les ingredients de la pizza
            $pizza->ingredients = AppRepoManager::getRm()->getPizzaIngredientRepository()->getIngredientByPizzaId($pizza->id);

            //on va hydrater les prix de la pizza
            $pizza->prices = AppRepoManager::getRm()->getPriceRepository()->getPriceByPizzaId($pizza->id);

            $array_result[] = $pizza;
        }

        return $array_result;
    }

    //on crée une méthode qui va chercher une pizza par son id
    public function getPizzaById(int $pizza_id): ?Pizza
    {
        //on crée la requête
        $query = sprintf(
            'SELECT * FROM %s WHERE `id`=:id',
            $this->getTableName()
        );
        //on prepare la requete
        $stmt = $this->pdo->prepare($query);

        // on verifie si la requete s'est bien preparer
        if (!$stmt) return null;

        //on execute le requête en bindant les paramètres
        $stmt->execute(['id' => $pizza_id]);

        //on recupere le resultat
        $result = $stmt->fetch();

        //si je n'ai pas de resultat on retourne null
        if (!$result) return null;

        //on retourne une instance de pizza
        $pizza = new Pizza($result);

        //on va hydrater les ingredients de la pizza
        $pizza->ingredients = AppRepoManager::getRm()->getPizzaIngredientRepository()->getIngredientByPizzaId($pizza->id);

        //on va hydrater les prix de la pizza
        $pizza->prices = AppRepoManager::getRm()->getPriceRepository()->getPriceByPizzaId($pizza->id);

        return $pizza;
    }

    //méthode pour créer une pizza
    public function insertPizza(array $data): ?Pizza
    {
        //on crée la requête
        $query = sprintf(
            'INSERT INTO %s (`name`, `image_path`, `is_active`, `user_id`)
            VALUES (:name, :image_path, :is_active, :user_id)',
            $this->getTableName()
        );

        //on prepare la requête
        $stmt = $this->pdo->prepare($query);

        //on verifie que la requête s'est bien preparée
        if(!$stmt) return null;

        //on execute la requête bindant les parametres
        $stmt->execute($data);

        // on recupere l'id de la pizza
        $pizza_id = $this->pdo->lastInsertId();

        // on retourne la pizza
        return $this->getPizzaById($pizza_id);
    }

    // // methode pour ajouter pizza au compte
    // public function addPizzaToUser(int $user_id, int $pizza_id): bool
    // {
    //     //on crée la requête
    //     $query = sprintf(
    //         'UPDATE %s SET `user_id`=:user_id WHERE `id`=:pizza_id',
    //         $this->getTableName()
    //     );

    //     //on prepare la requête
    //     $stmt = $this->pdo->prepare($query);

    //     //on verifie que la requête s'est bien preparée
    //     if(!$stmt) return false;

    //     //on execute la requête bindant les parametres
    //     $stmt->execute(['user_id' => $user_id, 'pizza_id' => $pizza_id]);

    //     return true;



    // }
}
