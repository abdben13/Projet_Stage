<?php
namespace App\Model;

use App\Helpers\Text;
use DateTime;

class Post {
    private $id;
    private $slug;
    private $name;
    private $content;
    private $created_at;
    private $categories = [];
    private $prix;
    private $kilometrage;
    private $mise_en_circulation;
    private $image_path;
    public function getName(): ?string
    {
        return $this->name;
    }
    public function getFormattedContent(): ?string
    {
        return nl2br(e($this->content));
    }
    public function getExcerpt(): ?string
    {
        if($this->content === null) {
            return null;
        }
        return nl2br(htmlentities(Text::excerpt($this->content, 60)));
    }
    public function getCreatedAt(): Datetime
    {
        return new DateTime($this->created_at);
    }
    public function getSlug (): ?string
    {
        return $this->slug;
    }
    public function getID (): ?int
    {
        return $this->id;
    }
    public function getPrix (): ?int
    {
        return $this->prix;
    }
    public function getKilometrage (): ?int
    {
        return $this->kilometrage;
    }
    public function getMise_en_circulation (): ?DateTime
    {
        return new DateTime($this->mise_en_circulation);
    }
    public function getImagePath(): ?string
    {
        return '/Projet_Stage/public/img/' . $this->image_path;
    }
}
