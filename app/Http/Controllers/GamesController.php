<?php

namespace App\Http\Controllers;

use App\Events\GameAction;
use App\Models\Game;
use App\Models\GamesPlayers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class GamesController extends Controller
{
    //
    public function index()
    {
        $available_game_ids = GamesPlayers::where(['state' => 'Waiting'])->pluck('game_id');

        return response()->json(Game::whereIn('id', $available_game_ids)->get());
    }

    public function history()
    {
        $user = Auth::user();
        $played_games = GamesPlayers::where([
            'player_id' => $user->id
        ])->get();

        $games_history = [];
        foreach($played_games as $played_game) {
            if ($played_game->state == 'Win' || $played_game->state == 'Loss') {
                $games_history[] = [
                    'name' => "Joc #{$played_game->game_id}",
                    'status' => $played_game->state,
                    'date' => $played_game->updated_at->toDateTimeString()
                ];
            }
        }

        return response()->json(['games' => $games_history]);
    }

    public function create()
    {
        $game = Game::create();

        return response()->json($game);
    }

    public function join($id)
    {
        $game = Game::find($id);
        $user = Auth::user();

        if ($game->players()->count() == 2) {
            return response()->json([], 422);
        } else {
            GamesPlayers::create([
                'game_id' => $game->id,
                'player_id' => $user->id,
                'state' => 'Waiting',
                'captured_nodes_count' => 0
            ]);
            $game->update_players_state();
            event(new GameAction("game" . $game->id, $game->state()));
    
            return response()->json([
                'my_player_id' => $user->id
            ]);
        }
    }

    public function player_action(Request $request, $id)
    {
        $game = Game::find($id);
        $game->action($request->input('action'), $request->input('node'));
        event(new GameAction("game" . $game->id, $game->state()));
        return response()->json();
    }
}
