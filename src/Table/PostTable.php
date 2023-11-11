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
    public $class = Post::class;
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
    public function findPostsByFilters(?int $marqueID, ?int $priceMax): array
    {
        $parameters = [];
        $conditions = [];

        if ($marqueID !== null) {
            $conditions[] = "pm.marque_id = :marqueID";
            $parameters[':marqueID'] = $marqueID;
        }

        if ($priceMax !== null) {
            $conditions[] = "p.prix <= :priceMax";
            $parameters[':priceMax'] = $priceMax;
        }

        $sql = "SELECT p.*
        FROM {$this->table} p
        LEFT JOIN post_marque pm ON p.id = pm.post_id";

        if (!empty($conditions)) {
            $sql .= " WHERE " . implode(" AND ", $conditions);
        }
        $query = $this->pdo->prepare($sql);
        $query->execute($parameters);
        return $query->fetchAll(PDO::FETCH_CLASS, $this->class);
    }


    public function update(Post $post): void
    {
        $query = $this->pdo->prepare("UPDATE {$this->table} SET name = :name, slug = :slug, mise_en_circulation = :mise_en_circulation, 
                 content = :content, kilometrage = :kilometrage, prix = :prix, energie = :energie WHERE id = :id");
        $ok = $query->execute([
            'id' => $post->getID(),
            'name' => $post->getName(),
            'slug' => $post->getSlug(),
            'content' => $post->getContent(),
            'prix' => $post->getPrix(),
            'kilometrage' => $post->getKilometrage(),
            'mise_en_circulation' => $post->getMise_en_circulation()->format('Y-m-d'),
            'energie' => $post->getEnergie()
        ]);
        if ($ok === false) {
            throw new Exception("L'annonce n'a pas pu être mise à jour {$post->getID()}");
        }
    }
    public function create(Post $post): void
    {
        $slug = $this->generateSlug($post->getName());
        $query = $this->pdo->prepare("INSERT INTO {$this->table} SET name = :name, slug = :slug, mise_en_circulation = :mise_en_circulation, 
                 content = :content, kilometrage = :kilometrage, prix = :prix, energie = :energie, created_at = :created_at");
        $ok = $query->execute([
            'name' => $post->getName(),
            'slug' => $slug,
            'content' => $post->getContent(),
            'prix' => $post->getPrix(),
            'kilometrage' => $post->getKilometrage(),
            'mise_en_circulation' => $post->getMise_en_circulation()->format('Y-m-d'),
            'energie' => $post->getEnergie(),
            'created_at' =>$post->getCreatedAt()->format('Y-m-d')
        ]);
        if ($ok === false) {
            throw new Exception("L'annonce n'a pas pu être créer {$post->getID()}");
        }
        $post->setID($this->pdo->lastInsertId());
    }
    private function generateSlug(string $name): string
    {
        return strtolower(str_replace(' ', '-', $name));
    }
    public function findAll(): array
    {
        $sql = "SELECT * FROM {$this->table} ORDER BY created_at DESC";
        return $this->pdo->query($sql, PDO::FETCH_CLASS, $this->class)->fetchAll();
    }
    public function getTable(): string
    {
        return $this->table;
    }
}