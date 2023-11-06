<?php
namespace App\Table;

    use App\Model\Marque;
    use App\Table\Exception\NotFoundException;
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
    }
