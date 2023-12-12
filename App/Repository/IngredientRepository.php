<?php

namespace App\Repository;

use App\Model\Ingredient;
use Core\Repository\Repository;

class IngredientRepository extends Repository
{
    public function getTableName(): string
    {
        return 'ingredient';
    }

    //méthode qui recupére la liste des ingredient actifs
    public function getIngredientActive() : array
    {
        //on déclare un tableau vide
        $array_result = [];
        //on creé la requête
        $query = sprintf(
            'SELECT * FROM %s WHERE is_active=1',
            $this->getTableName()
        );

        //on execute la requête
        $stmt = $this->pdo->query($query);
        // on verifie si la requete s'est bien executée
        if(!$stmt) return $array_result;

        //on recupere les resultats
        while($row_data = $stmt->fetch()){
            $array_result[] = new Ingredient($row_data);
        }

        return $array_result;
    }
}
