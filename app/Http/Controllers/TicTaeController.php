<?php

namespace App\Http\Controllers;

use App\Models\Game;
use Illuminate\Http\Request;

class TicTaeController extends Controller
{
   
    public function index()
    {
        $game = Game::first();
    
        if (!$game) {
            $game = Game::create([
                'board' => json_encode([["", "", ""], ["", "", ""], ["", "", ""]]),
                'current_player' => 'X'
            ]);
        }
    
        $board = json_decode($game->board, true);
        $winner = $this->checkWinner($board); // Check if there's a winner
    
        return view('tictactoe.index', ['board' => $board, 'currentPlayer' => $game->current_player, 'winner' => $winner]);
    }

    public function play(Request $request)
    {
        $game = Game::first();
    
        if (!$game) {
            return redirect()->route('tictactoe.index')->with('error', 'Game not found.');
        }
    
        $board = json_decode($game->board, true);
    
        $request->validate([
            'x' => 'required|integer|min:0|max:2',
            'y' => 'required|integer|min:0|max:2',
        ]);

    
        // Check if the game has already been won or is a draw
        $winner = $this->checkWinner($board);
        if ($winner) {
            return redirect()->route('tictactoe.index')->with('message', "Player $winner wins! The game is over.");
        }
    
        // Check for a draw
        if (!in_array('', array_merge(...$board))) {
            return redirect()->route('tictactoe.index')->with('message', 'The game is a draw! The game is over.');
        }
    
        // Check if the selected cell is empty
        if ($board[$request->x][$request->y] === '') {
            $board[$request->x][$request->y] = $game->current_player;
            $game->board = json_encode($board);
    
            // Check for a winner after the move
            $winner = $this->checkWinner($board);
            if ($winner) {
                $game->board = json_encode($board);
                $game->save();
                return redirect()->route('tictactoe.index')->with('message', "Player $winner wins! The game is over.");
            }
    
            // Switch player
            $game->current_player = $game->current_player === 'X' ? 'O' : 'X';
            $game->save();
        } else {
            return redirect()->route('tictactoe.index')->with('error', 'Cell already occupied.');
        }
    
        return redirect()->route('tictactoe.index')->with('message', 'Move made successfully.');
    }

    public function reset()
    {
        // Delete the existing game
        Game::truncate(); // This will remove all existing game entries
    
        // Create a new game entry
        Game::create([
            'board' => json_encode([["", "", ""], ["", "", ""], ["", "", ""]]),
            'current_player' => 'X'
        ]);
    
        return redirect()->route('tictactoe.index')->with('message', 'Game has been reset.');
    }

    private function checkWinner($board)
{
    // Check rows
    for ($i = 0; $i < 3; $i++) {
        if ($board[$i][0] && $board[$i][0] === $board[$i][1] && $board[$i][1] === $board[$i][2]) {
            return $board[$i][0]; // Return the winner (X or O)
        }
    }

    // Check columns
    for ($i = 0; $i < 3; $i++) {
        if ($board[0][$i] && $board[0][$i] === $board[1][$i] && $board[1][$i] === $board[2][$i]) {
            return $board[0][$i]; // Return the winner (X or O)
        }
    }

    // Check diagonals
    if ($board[0][0] && $board[0][0] === $board[1][1] && $board[1][1] === $board[2][2]) {
        return $board[0][0]; // Return the winner (X or O)
    }

    if ($board[0][2] && $board[0][2] === $board[1][1] && $board[1][1] === $board[2][0]) {
        return $board[0][2]; // Return the winner (X or O)
    }

    return null; // No winner
}

}
