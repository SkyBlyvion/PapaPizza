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
        $query = sprintf('SELECT `id`, `name`, `image_path` FROM %s WHERE `is_active`=1 AND `user_id`=1', $this->getTableName());

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
        if(!$result) return null;

        //on retourne une instance de pizza
        $pizza = new Pizza($result);

        //on va hydrater les ingredients de la pizza
        $pizza->ingredients = AppRepoManager::getRm()->getPizzaIngredientRepository()->getIngredientByPizzaId($pizza->id);

        //on va hydrater les prix de la pizza
        $pizza->prices = AppRepoManager::getRm()->getPriceRepository()->getPriceByPizzaId($pizza->id);

        return $pizza;
    }
}
