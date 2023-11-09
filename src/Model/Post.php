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
    public function setMise_en_circulation(?DateTime $mise_en_circulation): self
    {
        $this->mise_en_circulation = $mise_en_circulation;
        return $this;
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
