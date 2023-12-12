<?php

namespace App\Repository;

use App\Model\Size;
use Core\Repository\Repository;

class SizeRepository extends Repository
{
    public function getTableName(): string
    {
        return 'size';
    }

    //méthode qui récupére toutes les tailles
    public function getAllSize(): array
    {
        return $this->readAll(Size::class);
    }
}
