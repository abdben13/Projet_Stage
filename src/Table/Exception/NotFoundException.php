<?php
namespace App\Table\Exception;

class NotFoundException extends \Exception {
    public function __construct(string $table)
    {
        $this->message = "Aucun enregistement ne correspond dans la table '$table'";
    }
}
