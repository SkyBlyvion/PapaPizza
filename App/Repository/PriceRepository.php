<?php

namespace App\Repository;

use PDO;
use App\Model\Size;
use App\Model\Price;
use App\AppRepoManager;
use Core\Repository\Repository;

class PriceRepository extends Repository
{
    public function getTableName(): string
    {
        return 'price';
    }

    //methode qui va recuperer les prix d'une pizza ( 2 tables price et size + pizza_id )
    public function getPriceByPizzaId(int $pizza_id)
    {
        //on déclare un tableau vide
        $array_result = [];

        //on crée la requête
        $query = sprintf(
            'SELECT pr.*, s.label 
            FROM %1$s AS pr
            INNER JOIN %2$s AS s ON pr.size_id = s.id
            WHERE pr.pizza_id = :id',
            $this->getTableName(),
            AppRepoManager::getRm()->getSizeRepository()->getTableName()
        );

        //on prepare la requête
        $stmt = $this->pdo->prepare($query);

        // on verifie si la requete est bien prepaprée
        if (!$stmt) return $array_result;

        //on execute la requete en bindant les paramètres
        $stmt->execute(['id' => $pizza_id]);

        //on recupere les resultats
        while ($row_data = $stmt->fetch()) {
            $price = new Price($row_data);

            // on doit reconstruire un tableau afin de créer une instance de Size
            $size_data = [
                'id' => $row_data['size_id'],
                'label' => $row_data['label']
            ];

            // on crée une instance de size
            $size = new Size($size_data);

            //on hydrate price avec size
            $price->size = $size;

            //on ajoute l'objet Price dans le tableau
            $array_result[] = $price;
        }

        // on retourne le tableau rempli
        return $array_result;
    }

    //méthode pour créer un prix
    public function insertPrice(array $data): bool
    {
        //on crée la requete
        $query = sprintf(
            'INSERT INTO %1$s (`price`, `size_id`, `pizza_id`)
            VALUES (:price, :size_id, :pizza_id)',
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

    //methode pour update les prix d'une pizza
    public function updatePizzaPrice(array $data)
    {
        // On crée la requête
        $query = sprintf(
            'UPDATE %1$s SET `price` = :price WHERE `pizza_id` = :pizza_id AND `size_id` = :size_id',
            $this->getTableName()

        );

        //on prepare la requête
        $stmt = $this->pdo->prepare($query);

        // on vérifie que la requête est bien préparée
        if (!$stmt) {
            return false;
        }

        // On exécute la requête avec les données fournies
        return $stmt->execute($data);
    }
}
