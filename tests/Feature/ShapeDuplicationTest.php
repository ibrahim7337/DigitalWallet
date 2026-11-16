<?php

namespace Tests\Feature;

use Tests\TestCase;

class ShapeDuplicationTest extends TestCase
{
    public function test_shapes_can_be_duplicated_successfully(): void
    {
        $response = $this->postJson('/api/v1/duplicates/shape');

        $response->assertOk();

        $response->assertJsonPath(
            'original_circle.shape_type',
            'circle'
        );

        $response->assertJsonPath(
            'original_circle.color',
            'red'
        );

        $response->assertJsonPath(
            'original_circle.position.x',
            10
        );

        $response->assertJsonPath(
            'duplicated_circle.color',
            'red'
        );

        $response->assertJsonPath(
            'duplicated_circle.position.x',
            10
        );

        $response->assertJsonPath(
            'original_rectangle.shape_type',
            'rectangle'
        );

        $response->assertJsonPath(
            'original_rectangle.color',
            'blue'
        );

        $response->assertJsonPath(
            'duplicated_rectangle.color',
            'blue'
        );

        $response->assertJsonPath(
            'duplicated_rectangle.position.x',
            30
        );
    }
}
