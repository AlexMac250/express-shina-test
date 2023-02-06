<?php

namespace App\Entity\Trait;

use Doctrine\ORM\Mapping as ORM;

trait ResourcesTrait
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'AUTO')]
    #[ORM\Column(name: 'id', nullable: false)]
    private int $id;

    public function getId(): int
    {
        return $this->id;
    }
}
