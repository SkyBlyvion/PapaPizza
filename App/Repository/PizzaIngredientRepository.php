<?php

namespace App\Repository;

use PDO;
use App\AppRepoManager;
use App\Model\Ingredient;
use App\Model\PizzaIngredient;
use Core\Repository\Repository;

class PizzaIngredientRepository extends Repository
{
    public function getTableName(): string
    {
        return 'pizza_ingredient';
    }

    //méthode qui recupére la liste des ingredients d'une pizza
    public function getIngredientByPizzaId(int $pizza_id)
    {
        // on déclare un tableau vide
        $array_result = [];
        //on creé la requête
        $query = sprintf(
            'SELECT *
            FROM %1$s AS pi
            INNER JOIN %2$s AS i ON pi.ingredient_id = i.id
            WHERE pi.pizza_id=:id',
            $this->getTableName(),
            AppRepoManager::getRm()->getIngredientRepository()->getTableName()
        );
        //on prepare la requete
        $stmt = $this->pdo->prepare($query);

        // on verifie si la requete s'est bien preparer
        if (!$stmt) return $array_result;

        //on execute le requête en bindant les paramètres
        $stmt->execute(['id' => $pizza_id]);

        //on recupere les resultats
        while ($row_data = $stmt->fetch()) {
            $array_result[] = new Ingredient($row_data);
        }

        return $array_result;
    }

    //méthode pour créer une pizza_ingredient
    public function insertPizzaIngredient(array $data): bool
    {
        //on crée la requete
        $query = sprintf(
            'INSERT INTO %1$s (`pizza_id`, `ingredient_id`, `unit_id`, `quantity`)
            VALUES (:pizza_id, :ingredient_id, :unit_id, :quantity)',
            $this->getTableName()
        );

        //on prepare la requete
        $stmt = $this->pdo->prepare($query);

        //on verifie que le requete est bien preparée
        if (!$stmt) return false;

        //on execute le requete en bindant les paramètres
        $stmt->execute($data);

        return $stmt->rowCount() > 0;
    }

    //méthode pour supprimer les ingrédients d'une pizza
    public function deletePizzaIngredients(int $pizza_id): bool
    {
        //on crée la requête
        $query = sprintf(
            'DELETE FROM %1$s WHERE `pizza_id`=:pizza_id',
            $this->getTableName()
        );

        //on prepare la requête
        $stmt = $this->pdo->prepare($query);

        //on verifie que la requête est bien préparée
        if (!$stmt) {
            return false;
        }

        //on execute le requête en bindant les paramètres
        $stmt->execute(['pizza_id' => $pizza_id]);
        
        // vérifie si au moins une ligne a été affectée par la reqûete 
        //Si oui retourne true. Sinon, elle retourne false.
        return $stmt->rowCount() > 0;
    }
}
