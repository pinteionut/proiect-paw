<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GamesPlayers extends Model
{
    use HasFactory;
    protected $fillable = ['game_id', 'player_id', 'state', 'captured_nodes_count'];

    public function player() {
        return $this->belongsTo(User::class, 'player_id');
    }

    public function nodes() {
        return $this->hasMany(GamesPlayersNodes::class ,'game_player_id');
    }
}
