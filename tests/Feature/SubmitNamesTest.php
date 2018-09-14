<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Validation\ValidationException;

class SubmitNamesTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    function guest_can_submit_a_new_item()
    {
        $response = $this->post('/task', [
            'name' => 'Gerald Tarrent'
        ]);

        // Verify item now exists in database
        $this->assertDatabaseHas('tasks', [
            'name' => 'Gerald Tarrent'
        ]);

        // Verify 302 status code + Location header pointing to homepage
        $response
            ->assertStatus(302)
            ->assertHeader('Location', url('/'));

        // Verify the homepage has the created name
        $this
            ->get('/')
            ->assertSee('Gerald Tarrent');
    }

    /** @test */
    function name_is_not_created_if_validation_fails()
    {
        $response = $this->post('/task');
        $response->assertSessionHasErrors(['name']);
    }

    /** @test */
    function max_length_fails_when_too_long()
    {
        $this->withoutExceptionHandling();

        $name = str_repeat('a', 300);
        $exceeded = false;

        try{
            $this->post('/task', [
                'name' => $name
            ]);
        }
        catch (ValidationException $e) {
            $this->assertEquals(
                'The name may not be greater than 255 characters.',
                $e->validator->errors()->first('name')
            );
            $exceeded = true;
        }

        if($exceeded)
            $this->fail('Exceeded max length!');
        else
            $this->fail('Length passed');
    }


    /** @test */
    function max_length_succeeds_when_under_max()
    {
        $data = [
            'name' => str_repeat('a', 400)
        ];

        $this->post('/task', $data);
        $this->assertDatabaseHas('tasks', $data);
    }
}