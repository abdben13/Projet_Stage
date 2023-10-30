<?php
namespace App;

    class Router {
        private $viewPath;
        private $router;
        public function __construct(string $vieuwPath)
        {
            $this->viewPath = $vieuwPath;
            $this->router = new \AltoRouter();
        }
        public function get(string $url, string $view, ?string $name = null): self
        {
            $this->router->map('GET', $url, $view, $name);
            return $this;
        }
        public function run(): self
        {
            $match = $this->router->match();
            $view = $match['target'];
            ob_start();
            require $this->viewPath . DIRECTORY_SEPARATOR . $view . '.php';
            $content = ob_get_clean();
            require $this->viewPath . DIRECTORY_SEPARATOR . 'layouts/default.php';
            return $this;
        }
    }
