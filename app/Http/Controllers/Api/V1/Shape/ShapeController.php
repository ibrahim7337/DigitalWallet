<?php

namespace App\Http\Controllers\Api\V1\Shape;

use App\Http\Controllers\Controller;
use App\Prototypes\Circle;
use App\Prototypes\Rectangle;
use App\Shapes\Position;
use Illuminate\Http\JsonResponse;

class ShapeController extends Controller
{
    public function duplicate(): JsonResponse
    {
        $circle = new Circle('red', 2, new Position(10, 20));
        $rectangle = new Rectangle('blue', 3, new Position(30, 40));

        $duplicatedCircle = clone $circle;
        $duplicatedRectangle = clone $rectangle;

        return response()->json([
            'original_circle' => [
                'shape_type' => $circle->shapeType,
                'color' => $circle->color,
                'border_width' => $circle->borderWidth,
                'position' => [
                    'x' => $circle->position->x,
                    'y' => $circle->position->y,
                ],
            ],
            'duplicated_circle' => [
                'shape_type' => $duplicatedCircle->shapeType,
                'color' => $duplicatedCircle->color,
                'border_width' => $duplicatedCircle->borderWidth,
                'position' => [
                    'x' => $duplicatedCircle->position->x,
                    'y' => $duplicatedCircle->position->y,
                ],
            ],
            'original_rectangle' => [
                'shape_type' => $rectangle->shapeType,
                'color' => $rectangle->color,
                'border_width' => $rectangle->borderWidth,
                'position' => [
                    'x' => $rectangle->position->x,
                    'y' => $rectangle->position->y,
                ],
            ],
            'duplicated_rectangle' => [
                'shape_type' => $duplicatedRectangle->shapeType,
                'color' => $duplicatedRectangle->color,
                'border_width' => $duplicatedRectangle->borderWidth,
                'position' => [
                    'x' => $duplicatedRectangle->position->x,
                    'y' => $duplicatedRectangle->position->y,
                ],
            ],
        ]);
    }
}
