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

    //methode qui ajoute un user
    public function addUser(array $data) : ?User
    {
        $data_more = [
            'is_admin' => 0,
            'is_active' => 1,
        ];
        // on fusionne les deux tableaux
        $data = array_merge($data, $data_more);

        // on crée la requete d'insertion
        $query = sprintf(
            'INSERT INTO %s (`email`, `password`, `lastname`, `firstname`, `phone`, `is_admin`, `is_active`)
            VALUES ( :email, :password, :lastname, :firstname, :phone, :is_admin, :is_active)',
            $this->getTableName()
        );

        //on prepare la requete
        $stmt = $this->pdo->prepare($query);

        // on verifie que la requete est bien preparée
        if(!$stmt) return null;

        //on execute la requete
        $stmt->execute($data);

        //on recupere l'id du nouvel user
        $id = $this->pdo->lastInsertId();

        // on recupere l'user grace a cet id
        return $this->readById(User::class, $id);

    }
}
