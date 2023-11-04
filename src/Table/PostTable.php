<?php
namespace App\Table;

use App\Model\Marque;
use App\Model\Post;
use App\PaginatedQuery;
use App\Table\Exception\NotFoundException;
use PDO;

class PostTable extends Table{
    protected $table ="post";
    protected $class = Post::class;

    public function findPaginated() {
        $paginatedQuery = new PaginatedQuery(
            "SELECT * FROM post ORDER BY created_at DESC",
            "SELECT COUNT(id) FROM post",
            $this->pdo
        );
        $posts = $paginatedQuery->getItems(Post::class);
        (new MarqueTable($this->pdo))->hydratePosts($posts);
        return [$posts, $paginatedQuery];
    }
    public function findPaginatedMarque(int $marqueID) {
        $paginatedQuery = new PaginatedQuery(
            "SELECT p.* 
                    FROM post p 
                    JOIN post_marque pm ON pm.post_id = p.id
                    WHERE pm.marque_id = {$marqueID}
                    ORDER BY created_at DESC",
            "SELECT COUNT(marque_id) FROM post_marque WHERE marque_id = {$marqueID}"
        );
        $posts = $paginatedQuery->getItems(Post::class);
        (new MarqueTable($this->pdo))->hydratePosts($posts);
        return [$posts, $paginatedQuery];
    }
}