<?php

namespace App\Repository;

use App\Model\User;
use Core\Repository\Repository;

class UserRepository extends Repository
{
    public function getTableName(): string
    {
        return 'user';
    }

    //methode qui verifie si l'user existe en bdd
    public function findUserByEmail(string $email): ?User
    {
        // on prépare la requête
        $query = sprintf(
            'SELECT * FROM %s WHERE email = :email',
            $this->getTableName()
        );

        // faut preparer la requete
        $stmt = $this->pdo->prepare($query);

        // on verifir que la requete est bien preparee
        if (!$stmt) return null;

        // on execute la requête
        $stmt->execute(['email' => $email]);

        //on recupere le resultale de la requête
        while ($result = $stmt->fetch()) {
            $user = new User($result);
        }

        // on retourne user si il le connait et sinon on retourne null
        // equivalent : return $user ? $user : null
        return $user ?? null;
    }
}
