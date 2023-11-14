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


    public function updatePost (Post $post): void
    {
        $ok = $this->update([
            'id' => $post->getID(),
            'name' => $post->getName(),
            'slug' => $post->getSlug(),
            'content' => $post->getContent(),
            'prix' => $post->getPrix(),
            'kilometrage' => $post->getKilometrage(),
            'mise_en_circulation' => $post->getMise_en_circulation()->format('Y-m-d'),
            'energie' => $post->getEnergie()
        ], $post->getID());
        if ($ok === false) {
            throw new Exception("L'annonce n'a pas pu être mise à jour {$post->getID()}");
        }
    }
    public function createPost(Post $post): void
    {
        $slug = $this->generateSlug($post->getName());
        $id = $this->create([
            'name' => $post->getName(),
            'slug' => $slug,
            'content' => $post->getContent(),
            'prix' => $post->getPrix(),
            'kilometrage' => $post->getKilometrage(),
            'mise_en_circulation' => $post->getMise_en_circulation()->format('Y-m-d'),
            'energie' => $post->getEnergie(),
            'created_at' =>$post->getCreatedAt()->format('Y-m-d')
        ]);
        if ($id === false) {
            throw new Exception("L'annonce n'a pas pu être créer {$post->getID()}");
        }
        $post->setID($id);

    }
    public function attachMarques (int $id, array $marques)
    {
        $this->pdo->exec('DELETE FROM post_marque WHERE post_id = ' . $id);
        $query = $this->pdo->prepare('INSERT INTO post_marque SET post_id = ?, marque_id = ?');
        foreach ($marques as $marque) {
            $query->execute([$id, $marque]);
        }
    }
    protected function generateSlug(string $name): string
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