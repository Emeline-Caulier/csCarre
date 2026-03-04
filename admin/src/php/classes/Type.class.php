<?php

declare (strict_types=1);

class Type
{
    public function __construct(
        public readonly int    $id_type,
        public readonly string $nom_type
    )
    {
        //gestion des exceptions
    }

}
