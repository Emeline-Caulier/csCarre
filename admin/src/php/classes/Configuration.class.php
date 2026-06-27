<?php
declare(strict_types=1);

class Configuration
{
    public function __construct(
        public readonly string $cle,
        public readonly ?string $valeur
    ) {}
}