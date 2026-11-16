<?php

namespace App\Shapes;

abstract class Shape
{
    public string $shapeType;

    public function __construct(
        public string $color,
        public int $borderWidth,
        public Position $position
    ) {}

    abstract public function draw(): void;

    public function __clone()
    {
        $this->position = clone $this->position;
    }
}
