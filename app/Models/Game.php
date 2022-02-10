<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Providers\Node;

class Game extends Model
{
    use HasFactory;

    public function players()
    {
        return $this->hasMany(GamesPlayers::class);
    }

    public function state()
    {
        $players = $this->players;
        if ($players->count() != 2) {
            return [
                'state' => 'waiting_opponent'
            ];
        }
        $player1 = $players->get(0);
        $player2 = $players->get(1);

        $this->generate_nodes_graph();

        if ($this->is_over()) {
            GamesPlayersNodes::where([
                'game_player_id' => $this->current_player()->id
            ])->delete();
            GamesPlayersNodes::where([
                'game_player_id' => $this->other_player()->id
            ])->delete(); 
            $this->winner->update(['state' => 'Win']);
            $this->looser->update(['state' => 'Loss']);
            return [
                'state' => 'over',
                'players' => [
                    $player1->player_id => [
                        'state' => $player1->state,
                        'name' => $player1->player->name
                    ],
                    $player2->player_id => [
                        'state' => $player2->state,
                        'name' => $player2->player->name
                    ]
                ]
            ];
        };

        return [
            'state' => 'in_progress',
            'players' => [
                $player1->player_id => [
                    'state' => $player1->state,
                    'name' => $player1->player->name,
                    'nodesToPlace' => $this->player_nodes_to_place($player1->player_id, $player2),
                    'capturedNodes' => $player1->captured_nodes_count
                ],
                $player2->player_id => [
                    'state' => $player2->state,
                    'name' => $player2->player->name,
                    'nodesToPlace' => $this->player_nodes_to_place($player2->player_id, $player1),
                    'capturedNodes' => $player2->captured_nodes_count
                ]
            ],
            'suggestedNodes' => $this->get_suggested_nodes(),
            'tableNodes' => $this->get_table_nodes(),
            'millNodes' => $this->nodes_in_mill
        ];
    }

    public function update_players_state()
    {
        $players = $this->players;
        if ($players->count() == 1) {
            return;
        }
        $startingPlayer = rand(0, 1);
        $players[$startingPlayer]->update(['state' => 'Place']);
        $players[1 - $startingPlayer]->update(['state' => 'Opponent']);
    }

    public function get_nodes()
    {
        $nodes = [];
        $node = $this->entry_node;
        while ($node) {
            $nodes[] =  $node;
            $node = $node->next;
        }

        return $nodes;
    }

    public function get_table_nodes()
    {
        $nodes = [];

        foreach ($this->get_nodes() as $node) {
            $nodes[$node->id] =  $node->occupied_by;
        }

        return $nodes;
    }

    public function player_nodes_to_place($player_id, $other_player)
    {
        $nodesToPlace = 9;

        foreach ($this->get_nodes() as $node) {
            if ($node->occupied_by == $player_id) {
                $nodesToPlace--;
            };
        }

        $nodesToPlace -= $other_player->captured_nodes_count;

        return $nodesToPlace;
    }

    public function current_player()
    {
        $curr_player = null;

        foreach ($this->players as $player) {
            if ($player->state != 'Opponent') {
                $curr_player = $player;
            }
        }

        return $curr_player;
    }

    public function other_player()
    {
        $othr_player = null;

        foreach ($this->players as $player) {
            if ($player->state == 'Opponent') {
                $othr_player = $player;
            }
        }

        return $othr_player;
    }

    public function get_suggested_nodes()
    {
        $suggested_nodes = [];
        if ($this->current_player()->state == 'Place') {
            foreach ($this->get_nodes() as $node) {
                if ($node->occupied_by == null) {
                    $suggested_nodes[] = $node->id;
                };
            }
        }
        if ($this->current_player()->state == 'Attack') {
            foreach ($this->get_nodes() as $node) {
                if ($node->occupied_by != null && $node->occupied_by != $this->current_player()->player_id && !$node->in_mill()) {
                    $suggested_nodes[] = $node->id;
                };
            }
        }
        if ($this->current_player()->state == 'Move') {
            if ($this->current_player()->nodes()->count() == 3) {
                foreach ($this->get_nodes() as $node) {
                    if ($node->occupied_by == $this->current_player()->player_id) {
                        $suggested_nodes[] = $node->id;
                    };
                }
            } else {
                foreach ($this->get_nodes() as $node) {
                    if ($node->occupied_by == $this->current_player()->player_id && $node->empty_neighbors()) {
                        $suggested_nodes[] = $node->id;
                    };
                }
            }
        }
        if (str_starts_with($this->current_player()->state, 'Moving')) {
            if ($this->current_player()->nodes()->count() == 3) {
                foreach ($this->get_nodes() as $node) {
                    if ($node->occupied_by == null) {
                        $suggested_nodes[] = $node->id;
                    };
                }
            } else {
                $moving_node = null;
                foreach ($this->get_nodes() as $node) {
                    if ($node->id == explode(' ', $this->current_player()->state)[1]) {
                        $moving_node = $node;
                    };
                }
                $suggested_nodes = $moving_node->empty_neighbors();
            }
        }

        return $suggested_nodes;
    }

    public function action($action_type, $node_id)
    {
        switch ($action_type) {
            case 'Place':
                $this->place($node_id);
                break;
            case 'Attack':
                $this->attack($node_id);
                break;
            case 'Move':
                $this->select_node_to_move($node_id);
                break;
        }

        if (str_starts_with($action_type, 'Moving')) {
            $this->move_node($node_id, explode(' ', $action_type)[1]);
        }
    }

    public function place($node_id)
    {
        GamesPlayersNodes::create([
            'game_player_id' => $this->current_player()->id,
            'node_id' => $node_id
        ]);
        $this->generate_nodes_graph();

        $occupied_node = null;
        foreach ($this->get_nodes() as $node) {
            if ($node->id == $node_id) {
                $occupied_node = $node;
            };
        }

        if ($occupied_node->in_mill()) {
            $this->nodes_in_mill = $occupied_node->in_mill();
        }

        $current_player = $this->current_player();
        $other_player = $this->other_player();

        if ($occupied_node->in_mill()) {
            $current_player->update(['state' => 'Attack']);
        } else {
            $current_player->update(['state' => 'Opponent']);
            if ($this->player_nodes_to_place($other_player->player_id, $current_player) == 0) {
                $other_player->update(['state' => 'Move']);
            } else {
                $other_player->update(['state' => 'Place']);
            }
        }
    }

    public function attack($node_id)
    {
        GamesPlayersNodes::where([
            'game_player_id' => $this->other_player()->id,
            'node_id' => $node_id
        ])->delete();
        $this->current_player()->update([
            'captured_nodes_count' => $this->current_player()->captured_nodes_count + 1
        ]);
        $this->generate_nodes_graph();

        $current_player = $this->current_player();
        $other_player = $this->other_player();
        $current_player->update(['state' => 'Opponent']);
        if ($this->player_nodes_to_place($other_player->player_id, $current_player) == 0) {
            $other_player->update(['state' => 'Move']);
        } else {
            $other_player->update(['state' => 'Place']);
        }
    }

    public function select_node_to_move($node_id)
    {
        $this->current_player()->update(['state' => "Moving {$node_id}"]);
    }

    public function move_node($node_id, $old_position)
    {
        GamesPlayersNodes::where([
            'game_player_id' => $this->current_player()->id,
            'node_id' => $old_position
        ])->delete();
        $this->place($node_id);
    }

    public function is_over() {
        if (empty($this->get_suggested_nodes())) {
            $this->winner = $this->other_player();
            $this->looser = $this->current_player();
            return true;
        }

        if ($this->other_player()->captured_nodes_count == 7) {
            $this->winner = $this->other_player();
            $this->looser = $this->current_player();
            return true;
        }

        return false;
    }

    public function generate_nodes_graph()
    {
        $nodes = [];
        $nodes['big0'] = new Node('big0', null, null, null, null, null);
        $this->entry_node = $nodes['big0'];

        $nodes['big1'] = new Node('big1', null, null, null, $nodes['big0'], $nodes['big0']);
        $nodes['big2'] = new Node('big2', null, null, null, $nodes['big1'], $nodes['big1']);
        $nodes['big3'] = new Node('big3', $nodes['big2'], null, null, null, $nodes['big2']);
        $nodes['big4'] = new Node('big4', $nodes['big3'], null, null, null, $nodes['big3']);
        $nodes['big5'] = new Node('big5', null, $nodes['big4'], null, null, $nodes['big4']);
        $nodes['big6'] = new Node('big6', null, $nodes['big5'], null, null, $nodes['big5']);
        $nodes['big7'] = new Node('big7', $nodes['big0'], null, $nodes['big6'], null, $nodes['big6']);

        $nodes['mdm0'] = new Node('mdm0', null, null, null, null, $nodes['big7']);
        $nodes['mdm1'] = new Node('mdm1', $nodes['big1'], null, null, $nodes['mdm0'], $nodes['mdm0']);
        $nodes['mdm2'] = new Node('mdm2', null, null, null, $nodes['mdm1'], $nodes['mdm1']);
        $nodes['mdm3'] = new Node('mdm3', $nodes['mdm2'], $nodes['big3'], null, null, $nodes['mdm2']);
        $nodes['mdm4'] = new Node('mdm4', $nodes['mdm3'], null, null, null, $nodes['mdm3']);
        $nodes['mdm5'] = new Node('mdm5', null, $nodes['mdm4'], $nodes['big5'], null, $nodes['mdm4']);
        $nodes['mdm6'] = new Node('mdm6', null, $nodes['mdm5'], null, null, $nodes['mdm5']);
        $nodes['mdm7'] = new Node('mdm7', $nodes['mdm0'], null, $nodes['mdm6'], $nodes['big7'], $nodes['mdm6']);


        $nodes['sml0'] = new Node('sml0', null, null, null, null, $nodes['mdm7']);
        $nodes['sml1'] = new Node('sml1', $nodes['mdm1'], null, null, $nodes['sml0'], $nodes['sml0']);
        $nodes['sml2'] = new Node('sml2', null, null, null, $nodes['sml1'], $nodes['sml1']);
        $nodes['sml3'] = new Node('sml3', $nodes['sml2'], $nodes['mdm3'], null, null, $nodes['sml2']);
        $nodes['sml4'] = new Node('sml4', $nodes['sml3'], null, null, null, $nodes['sml3']);
        $nodes['sml5'] = new Node('sml5', null, $nodes['sml4'], $nodes['mdm5'], null, $nodes['sml4']);
        $nodes['sml6'] = new Node('sml6', null, $nodes['sml5'], null, null, $nodes['sml5']);
        $nodes['sml7'] = new Node('sml7', $nodes['sml0'], null, $nodes['sml6'], $nodes['mdm7'], $nodes['sml6']);

        $player_1_nodes = $this->players[0]->nodes->pluck('node_id');
        foreach ($player_1_nodes as $node_id) {
            $nodes[$node_id]->occupied_by = $this->players[0]->player_id;
        }
        $player_2_nodes = $this->players[1]->nodes->pluck('node_id');
        foreach ($player_2_nodes as $node_id) {
            $nodes[$node_id]->occupied_by = $this->players[1]->player_id;
        }
    }
}
