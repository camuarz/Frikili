<?php

namespace App\Entity;

use App\Repository\PostsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PostsRepository::class)]
class Posts
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private $Titulo;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $Foto;

    #[ORM\Column(type: 'datetime')]
    private $Fecha_publicacion;

    #[ORM\Column(type: 'string', length: 1000, nullable: true)]
    private $lik;

    #[ORM\Column(type: 'string', length: 80000)]
    private $Contenido;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'posts')]
    private $User;

    #[ORM\OneToMany(mappedBy: 'Posts', targetEntity: Comentarios::class)]
    private $Comentarios;

    public function __construct()
    {
        $this->lik='';
        $this->Fecha_publicacion = new \DateTime();
        $this->Comentarios = new ArrayCollection();
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitulo(): ?string
    {
        return $this->Titulo;
    }

    public function setTitulo(string $Titulo): self
    {
        $this->Titulo = $Titulo;

        return $this;
    }

    public function getFoto(): ?string
    {
        return $this->Foto;
    }

    public function setFoto(string $Foto): self
    {
        $this->Foto = $Foto;

        return $this;
    }

    public function getFechaPublicacion(): ?\DateTimeInterface
    {
        return $this->Fecha_publicacion;
    }

    public function setFechaPublicacion(\DateTimeInterface $Fecha_publicacion): self
    {
        $this->Fecha_publicacion = $Fecha_publicacion;

        return $this;
    }

    public function getlik(): ?string
    {
        return $this->lik;
    }

    public function setlik(?string $lik): self
    {
        $this->lik = $lik;

        return $this;
    }

    public function getContenido(): ?string
    {
        return $this->Contenido;
    }

    public function setContenido(string $Contenido): self
    {
        $this->Contenido = $Contenido;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->User;
    }

    public function setUser(?User $User): self
    {
        $this->User = $User;

        return $this;
    }

    /**
     * @return Collection|Comentarios[]
     */
    public function getComentarios(): Collection
    {
        return $this->Comentarios;
    }
    public function setComentarios(?Comentarios $comentarios): self
    {
        $this->Comentarios = $comentarios;

        return $this;
    }
    public function addComentario(Comentarios $comentario): self
    {
        if (!$this->Comentarios->contains($comentario)) {
            $this->Comentarios[] = $comentario;
            $comentario->setPosts($this);
        }

        return $this;
    }

    public function removeComentario(Comentarios $comentario): self
    {
        if ($this->Comentarios->removeElement($comentario)) {
            // set the owning side to null (unless already changed)
            if ($comentario->getPosts() === $this) {
                $comentario->setPosts(null);
            }
        }

        return $this;
    }





}
