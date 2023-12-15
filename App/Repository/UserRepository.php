<?php

namespace App\Repository;

use App\Model\User;
use Core\Session\Session;
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
    public function addUser(array $data): ?User
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
        if (!$stmt) return null;

        //on execute la requete
        $stmt->execute($data);

        //on recupere l'id du nouvel user
        $id = $this->pdo->lastInsertId();

        // on recupere l'user grace a cet id
        return $this->readById(User::class, $id);
    }

    //methode qui ajoute un user
    public function addTeam(array $data): ?User
    {
        $data_more = [
            'is_admin' => 1,
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
        if (!$stmt) return null;

        //on execute la requete
        $stmt->execute($data);

        //on recupere l'id du nouvel user
        $id = $this->pdo->lastInsertId();

        // on recupere l'user grace a cet id
        return $this->readById(User::class, $id);
    }

    //methode qui recupere un user par son id
    public function findUserById(int $id): ?User
    {
        return $this->readById(User::class, $id);
    }

    //methode qui recupere tous les user qui ne sonts pas admin et qui sont actifs
    public function getAllClientsActif(): array
    {
        // on déclare un tableau vide
        $users = [];
        // on crée la requête
        $query = sprintf(
            'SELECT * FROM %s WHERE is_admin = 0 AND is_active = 1',
            $this->getTableName()
        );
        // on peut directement executer la requete
        $stmt = $this->pdo->query($query);

        //on vérifie que la requete est bien exceuter
        if (!$stmt) return $users;

        // on recupere les resultats
        while ($result = $stmt->fetch()) {
            $users[] = new User($result);
        }

        return $users;
    }

    //methode qui recupere tous les employées qui sont actifs
    public function getAllTeamActif(): array
    {
        // on déclare un tableau vide
        $users = [];
        // on crée la requête
        $query = sprintf(
            'SELECT * FROM %s WHERE is_admin = 1 AND is_active = 1',
            $this->getTableName()
        );
        // on peut directement executer la requete
        $stmt = $this->pdo->query($query);

        //on vérifie que la requete est bien exceuter
        if (!$stmt) return $users;

        // on recupere les resultats
        while ($result = $stmt->fetch()) {
            $users[] = new User($result);
        }

        return $users;
    }

    //methode qui désactive un user
    public function deleteUser(int $id): bool
    {
        // on crée la requête
        $query = sprintf(
            'UPDATE %s SET `is_active` = 0 WHERE `id` = :id',
            $this->getTableName()
        );

        // on prépare la reqûete
        $stmt = $this->pdo->prepare($query);

        // on verifie que la requete est bvien prepapree
        if (!$stmt) return false;

        // on execute la requête si la requete est passée on retourne true sinon false
        return $stmt->execute(['id' => $id]);
    }

    //methode qui désactive un admin ou membre équipe
    public function deleteUserAdmin(int $id): bool
    {
        // on crée la requête
        $query = sprintf(
            'UPDATE %s SET `is_active` = 0 WHERE `id` = :id',
            $this->getTableName()
        );

        // on prépare la reqûete
        $stmt = $this->pdo->prepare($query);

        // on verifie que la requete est bvien prepapree
        if (!$stmt) return false;

        // on execute la requête si la requete est passée on retourne true sinon false
        return $stmt->execute(['id' => $id]);
    }


    //methode qui permet a un user de modifier ses informations
    public function updateUser(array $data): bool
    {
        var_dump($data);
        // on crée la requête
        $query = sprintf(
            'UPDATE %s SET `email` = :email, `lastname` = :lastname, `firstname` = :firstname, `phone` = :phone WHERE `id` = :id',
            // "UPDATE %s SET firstname = 'toto' WHERE `id` = :id",
            $this->getTableName()
        );
        var_dump($query);
        // on prépare la requête
        $stmt = $this->pdo->prepare($query);

        // on vérifie que la requête est bien préparée
        if (!$stmt) {
            return false;
        }

        // on execute la requête
        // $data['id'] = $id;
        return $stmt->execute($data);
    }
}
