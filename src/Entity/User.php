<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    const REGISTRO_EXITOSO = 'Se ha registrado exitosamente';
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 180, unique: true)]
    private $email;

    #[ORM\Column(type: 'json')]
    private $roles = [];

    #[ORM\Column(type: 'string')]
    private $password;

    #[ORM\Column(type: 'boolean')]
    private $Baneado; 

    #[ORM\Column(type: 'string')]
    Private $Nombre;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Comentarios", mappedBy="user")
     */
    private $comentarios;

    #[ORM\OneToMany(mappedBy: 'User', targetEntity: Comentarios::class)]
    private $Comentarios;

    #[ORM\OneToMany(mappedBy: 'User', targetEntity: Posts::class)]
    private $posts;

    #[ORM\ManyToMany(targetEntity: Profesión::class, mappedBy: 'User')]
    private $profesiNs;

    public function __construct()
    {
        $this->Baneado = false;
        $this->roles = ['ROLE_USER'];
        $this->Comentarios = new ArrayCollection();
        $this->posts = new ArrayCollection();
        $this->profesiNs = new ArrayCollection();
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }
    
    /**
     * @return mixed
     */
    public function getNombre()
    {
        return $this->Nombre;
    }

    /**
     * @param mixed $Nombre
     */
    public function setNombre($Nombre): void
    {
        $this->Nombre = $Nombre;
    }


    /**
     * @return mixed
     */
    public function getBaneado()
    {
        return $this->Baneado;
    }

    /**
     * @param mixed $Baneado
     */
    public function setBaneado($Baneado): void
    {
        $this->Baneado = $Baneado;
    }



    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    /**
     * @return Collection|Comentarios[]
     */
    public function getComentarios(): Collection
    {
        return $this->Comentarios;
    }

    public function addComentario(Comentarios $comentario): self
    {
        if (!$this->Comentarios->contains($comentario)) {
            $this->Comentarios[] = $comentario;
            $comentario->setUser($this);
        }

        return $this;
    }

    public function removeComentario(Comentarios $comentario): self
    {
        if ($this->Comentarios->removeElement($comentario)) {
            // set the owning side to null (unless already changed)
            if ($comentario->getUser() === $this) {
                $comentario->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Posts[]
     */
    public function getPosts(): Collection
    {
        return $this->posts;
    }

    public function addPost(Posts $post): self
    {
        if (!$this->posts->contains($post)) {
            $this->posts[] = $post;
            $post->setUser($this);
        }

        return $this;
    }

    public function removePost(Posts $post): self
    {
        if ($this->posts->removeElement($post)) {
            // set the owning side to null (unless already changed)
            if ($post->getUser() === $this) {
                $post->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Profesión[]
     */
    public function getProfesiNs(): Collection
    {
        return $this->profesiNs;
    }

    public function addProfesiN(Profesión $profesiN): self
    {
        if (!$this->profesiNs->contains($profesiN)) {
            $this->profesiNs[] = $profesiN;
            $profesiN->addUser($this);
        }

        return $this;
    }

    public function removeProfesiN(Profesión $profesiN): self
    {
        if ($this->profesiNs->removeElement($profesiN)) {
            $profesiN->removeUser($this);
        }

        return $this;
    }


}

    

