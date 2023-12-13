<?php

namespace App\Repository;

use App\AppRepoManager;
use App\Model\Ingredient;
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
    public function insertPizzaIngredient(array $data):bool
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
        if(!$stmt) return false;

        //on execute le requete en bindant les paramètres
        $stmt->execute($data);

        return $stmt->rowCount() > 0;
    }
}
