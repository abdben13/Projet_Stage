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
    private $marques = [];
    private $prix;
    private $kilometrage;
    private $mise_en_circulation;
    private $image_path;
    private $energie;
    public function getName(): ?string
    {
        return $this->name;
    }
    public function setName(string $name): self
    {
        $this->name = $name;
        return $this;
    }
    public function getContent(): ?string
    {
        return $this->content;
    }
    public function setContent(string $content): self
    {
        $this->content = $content;
        return $this;
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
    public function setCreatedAt(string $date): self
    {
        $this->created_at = $date;
        return $this;
    }
    public function getSlug (): ?string
    {
        return $this->slug;
    }
    public function getID (): ?int
    {
        return $this->id;
    }
    public function setID (int $id): self
    {
        $this->id = $id;
        return $this;
    }
    public function getPrix (): ?int
    {
        return $this->prix;
    }
    public function setPrix(int $prix): self
    {
        $this->prix = $prix;
        return $this;
    }
    public function getKilometrage (): ?int
    {
        return $this->kilometrage;
    }
    public function setKilometrage(int $kilometrage): self
    {
        $this->kilometrage = $kilometrage;
        return $this;
    }
    public function getMise_en_circulation (): ?DateTime
    {
        return new DateTime($this->mise_en_circulation);
    }
    public function setMiseencirculation(string $date): self
    {
        $this->mise_en_circulation = $date;
        return $this;
    }
    public function getMarquesIds (): array
    {
        $ids = [];
        foreach ($this->marques as $marque) {
            $ids[] = $marque->getID();
        }
        return $ids;
    }


    public function getImagePath(): ?string
    {
        return $this->image_path;

    }

    /**
     * @return Marque[]
     */
    public function getMarques(): array
    {
        return $this->marques;
    }
    public function setMarques(array $marques): self
    {
        $this->marques = $marques;
        return $this;
    }
    public function addMarque(Marque $marque): void
    {
        $this->marques[] = $marque;
        $marque->setPost($this);
    }
    public function getEnergie(): ?string
    {
        return $this->energie;
    }
    public function setEnergie(string $energie): self
    {
        $this->energie = $energie;
        return $this;
    }
}
