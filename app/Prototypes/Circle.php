<?php

namespace App\Prototypes;

use App\Shapes\Position;
use App\Shapes\Shape;

class Circle extends Shape
{
    public string $shapeType = 'circle';

    public function __construct(string $color, int $borderWidth, Position $position)
    {
        parent::__construct($color, $borderWidth, $position);
    }

    public function draw(): void
    {
        // Implementation for drawing a circle
    }
}
