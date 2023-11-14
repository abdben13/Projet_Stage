<?php
namespace App\Table;

use App\Model\Post;
use App\Table\Exception\NotFoundException;
use Exception;
use PDO;
 class Table {
    protected $pdo;
    protected $table = null;
    protected $class = null;
    public function __construct(PDO $pdo)
    {
        if ($this->table == null) {
            throw new Exception("La classe " . get_class($this) . " n'a pas de propriété \$table");
        }
        if ($this->class == null) {
            throw new Exception("La classe " . get_class($this) . " n'a pas de propriété \$class");
        }
        $this->pdo = $pdo;
    }
    public function find (int $id)
    {
        $query = $this->pdo->prepare('SELECT * FROM ' . $this->table .' WHERE id = :id');
        $query->execute(['id' => $id]);
        $query->setFetchMode(PDO::FETCH_CLASS, $this->class);
        $result = $query->fetch();
        if ($result === false) {
            throw new NotFoundException($this->table, $id);
        }
        return $result;
    }
    public function all (): array
    {
        $sql = "SELECT * FROM {$this->table}";
        return $this->pdo->query($sql, PDO::FETCH_CLASS, $this->class)->fetchAll();
    }
    public function delete (int $id)
    {
        $query = $this->pdo->prepare("DELETE FROM {$this->table} WHERE id = ?");
        $ok = $query->execute([$id]);
        if ($ok === false) {
            throw new Exception("Impossible de supprimer l'enregistrement $id");
        }
    }
    public function create (array $data): int
    {
        if (isset($data['name'])) {
            $data['slug'] = $this->generateSlug($data['name']);
        }
        $sqlFields = [];
        foreach ($data as $key => $value) {
            $sqlFields[] = "$key = :$key";
        }
        $query = $this->pdo->prepare("INSERT INTO {$this->table} SET " . implode(', ', $sqlFields));
        $ok = $query->execute($data);
        if ($ok === false) {
            throw new Exception("L'enregistrement n'a pas pu être créer {$this->table}");
        }
        return (int)$this->pdo->lastInsertId();
    }
    public function update (array $data, int $id)
    {
        if (isset($data['name'])) {
            $data['slug'] = $this->generateSlug($data['name']);
        }
        $sqlFields = [];
        foreach ($data as $key => $value) {
            $sqlFields[] = "$key = :$key";
        }
        $query = $this->pdo->prepare("UPDATE {$this->table} SET " . implode(', ', $sqlFields) . " WHERE id = :id");
        $ok = $query->execute(array_merge($data, ['id' => $id]));
        if ($ok === false) {
            throw new Exception("L'enregistrement n'a pas pu être modifié {$this->table}");
        }
    }
     protected function queryAndFetchAll(string $sql): array
     {
         $query = $this->pdo->query($sql);
         return $query->fetchAll(PDO::FETCH_CLASS, $this->class);
     }
}
