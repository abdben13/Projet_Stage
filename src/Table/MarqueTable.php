<?php
namespace App\Table;

use App\Model\Marque;
use App\Model\Post;
use App\Table\Exception\NotFoundException;
use Exception;
use PDO;

class MarqueTable extends Table {
    protected $table = "marque";
    protected $class = Marque::class;

    /**
     * @param array $posts
     * @return void
     */
    public function hydratePosts (array $posts): void
    {
        $postsByID = [];
        foreach ($posts as $post) {
            $postsByID[$post->getID()] = $post;
        }

        $marque = $this->pdo
            ->query('SELECT m.*, pm.post_id
            FROM post_marque pm
            JOIN marque m ON m.id = pm.marque_id
            WHERE pm.post_id IN (' . implode(',', array_keys($postsByID)) . ')'
            )->fetchAll(PDO::FETCH_CLASS, $this->class);

        foreach ($marque as $marques) {
            $postsByID[$marques->getPostID()]->addMarque($marques);
        }
    }
    public function findAll()
    {
        $query = $this->pdo->query("SELECT * FROM {$this->table}");
        return $query->fetchAll(PDO::FETCH_CLASS, $this->class);
    }
    protected function generateSlug(string $name): string
    {
        return strtolower(str_replace(' ', '-', $name));
    }
    public function all (): array
    {
        return $this->queryAndFetchAll("SELECT * FROM {$this->table} ORDER BY id DESC");
    }
    public function list (): array
    {
        $query = $this->pdo->query("SELECT * FROM {$this->table} ORDER BY name ASC");
        $marques = $query->fetchAll(PDO::FETCH_CLASS, $this->class);
        $results = [];
        foreach ($marques as $marque) {
            $results[$marque->getID()] = $marque->getName();
        }
        return $results;
    }


}
