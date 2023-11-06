<?php
namespace App\Table;

use App\Model\Marque;
use App\Model\Post;
use App\PaginatedQuery;
use App\Table\Exception\NotFoundException;
use DateTime;
use Exception;
use PDO;

class PostTable extends Table{
    protected $table ="post";
    protected $class = Post::class;
    public function delete (int $id): void
    {
        $query = $this->pdo->prepare("DELETE FROM {$this->table} WHERE id = ?");
        $ok = $query->execute([$id]);
        if ($ok === false) {
            throw new Exception("Impossible de supprimer l'annonce $id");
        }
    }

    public function findPaginated() {
        $paginatedQuery = new PaginatedQuery(
            "SELECT * FROM {$this->table} ORDER BY created_at DESC",
            "SELECT COUNT(id) FROM {$this->table}",
            $this->pdo
        );
        $posts = $paginatedQuery->getItems(Post::class);
        (new MarqueTable($this->pdo))->hydratePosts($posts);
        return [$posts, $paginatedQuery];
    }

    public function findPaginatedMarque(int $marqueID)
    {
        $paginatedQuery = new PaginatedQuery(
            "SELECT p.* 
            FROM {$this->table} p 
            JOIN post_marque pm ON pm.post_id = p.id
            WHERE pm.marque_id = {$marqueID}
            ORDER BY created_at DESC",
            "SELECT COUNT(marque_id) FROM post_marque WHERE marque_id = {$marqueID}"
        );
        $posts = $paginatedQuery->getItems(Post::class);
        (new MarqueTable($this->pdo))->hydratePosts($posts);
        return [$posts, $paginatedQuery];
    }

    public function updateFields(Post $post, array $fields): void
    {
        $setClauses = [];
        $values = [];
        foreach ($fields as $field => $value) {
            if ($value instanceof DateTime) {
                $value = $value->format('Y-m-d H:i:s');
            }
            $setClauses[] = "$field = :$field";
            $values[":$field"] = $value;
        }

        $query = $this->pdo->prepare("UPDATE {$this->table} SET " . implode(', ', $setClauses) . " WHERE id = :id");
        $values[':id'] = $post->getID();

        $ok = $query->execute($values);
        if ($ok === false) {
            throw new Exception("L'annonce n'a pas pu être mise à jour {$post->getID()}");
        }
    }
    public function findAll(): array
    {
        $sql = "SELECT * FROM {$this->table} ORDER BY created_at DESC";
        return $this->pdo->query($sql, PDO::FETCH_CLASS, $this->class)->fetchAll();
    }

}