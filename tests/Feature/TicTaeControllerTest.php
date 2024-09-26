<?php

namespace Tests\Feature;

use App\Models\Game;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TicTaeControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_displays_the_game_index_and_creates_a_new_game_if_none_exists()
    {
        $response = $this->get('/'); // Visit the index route

        $response->assertStatus(200);
        $response->assertViewIs('tictactoe.index');

        // Check that a new game is created in the database
        // $this->assertDatabaseHas('games', [
        //     'current_player' => 'X',
        //     'board' => json_encode([["", "", ""], ["", "", ""], ["", "", ""]]),
        // ]);
    }

    /** @test */
    public function it_allows_a_player_to_make_a_move()
    {
        // Create a new game
        // $game = Game::create([
        //     'board' => json_encode([["", "", ""], ["", "", ""], ["", "", ""]]),
        //     'current_player' => 'X'
        // ]);

        // Simulate a player making a move
        $response = $this->post(route('tictactoe.play'), ['x' => 1, 'y' => 0]);

        $response->assertRedirect(route('tictactoe.index'));


        $response = $this->followRedirects($response); // Follow the redirect to get the final response

        // Assert that the final response status is 200
        $this->assertEquals(200, $response->getStatusCode());
    

        $response->assertStatus(200);


        // Check the updated game state
        // $this->assertDatabaseHas('games', [
        //     'board' => json_encode([["X", "", ""], ["", "", ""], ["", "", ""]]),
        //     'current_player' => 'O' // Next player
        // ]);
    }

    /** @test */
    public function it_does_not_allow_a_player_to_make_a_move_in_an_occupied_cell()
    {
        // Create a new game with a move already made
        // Game::create([
        //     'board' => json_encode([["X", "", ""], ["", "", ""], ["", "", ""]]),
        //     'current_player' => 'O'
        // ]);

        // Attempt to make a move in an occupied cell
        $response = $this->post(route('tictactoe.play'), ['x' => 1, 'y' => 0]);

        $response->assertRedirect(route('tictactoe.index'));
        $response->assertSessionHas('error', 'Cell already occupied.');

        // Assert that the board remains unchanged
        // $this->assertDatabaseHas('games', [
        //     'board' => json_encode([["X", "", ""], ["", "", ""], ["", "", ""]]),
        //     'current_player' => 'O'
        // ]);
    }

    /** @test */
    public function it_resets_the_game()
    {
        // Create a game
        Game::create([
            'board' => json_encode([["X", "", ""], ["", "", ""], ["", "", ""]]),
            'current_player' => 'O'
        ]);

        $this->assertDatabaseCount('games', 1); // Before reset

        // Call the reset route
        $response = $this->post(route('tictactoe.reset'));

        $response->assertRedirect(route('tictactoe.index'));

        // Assert that the game has been reset in the database
        $this->assertDatabaseCount('games', 1); // Still one game should exist
        // $this->assertDatabaseHas('games', [
        //     'board' => json_encode([["", "", ""], ["", "", ""], ["", "", ""]]),
        //     'current_player' => 'X' // Player X should be starting again
        // ]);
    }
}
