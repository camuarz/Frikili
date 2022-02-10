<?php

namespace App\Entity;

use App\Repository\ComentariosRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ComentariosRepository::class)]
class Comentarios
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 80000)]
    private $Comentario;

    #[ORM\Column(type: 'datetime')]
    private $Fecha_publicacion;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'Comentarios')]
    private $User;

    #[ORM\ManyToOne(targetEntity: Posts::class, inversedBy: 'Comentarios')]
    #[ORM\JoinColumn(nullable: false)]
    private $Posts;

    public function __construct()
    {
        $this->Fecha_publicacion = new \DateTime();
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getComentario(): ?string
    {
        return $this->Comentario;
    }

    public function setComentario(string $Comentario): self
    {
        $this->Comentario = $Comentario;

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

    public function getUser(): ?User
    {
        return $this->User;
    }

    public function setUser(?User $User): self
    {
        $this->User = $User;

        return $this;
    }

    public function getPosts(): ?Posts
    {
        return $this->Posts;
    }

    public function setPosts(?Posts $Posts): self
    {
        $this->Posts = $Posts;

        return $this;
    }
}
