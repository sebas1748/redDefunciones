<?php

namespace App\Entity;

use App\Repository\UsuarioPerfilRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=UsuarioPerfilRepository::class)
 */
class UsuarioPerfil
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=40)
     */
    private $descripcion;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Usuario", mappedBy="perfiles")
     */
    private $usuarios;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDescripcion(): ?string
    {
        return $this->descripcion;
    }

    public function setDescripcion(string $descripcion): self
    {
        $this->descripcion = $descripcion;

        return $this;
    }
}
