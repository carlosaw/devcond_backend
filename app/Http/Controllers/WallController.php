<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\User;
use App\Models\Wall;
use App\Models\WallLike;

class WallController extends Controller
{
    public function getAll() {
        $array = ['error' => '', 'list' => []];
        // Pega usuário logado
        $user = auth()->user();

        // Pega todos os avisos do Mural
        $walls = Wall::all();

        // Pega likes (se like ou não)
        foreach($walls as $wallKey => $wallValue) {
            $walls[$wallKey]['likes'] = 0;
            $walls[$wallKey]['liked'] = false;
            // Pega qtde de likes
            $likes = WallLike::where('id_wall', $wallValue['id'])->count();
            $walls [$wallKey]['likes'] = $likes;
            // Se usuário logado deu like ou não
            $meLikes = WallLike::where('id_wall', $wallValue['id'])
            ->where('id_user', $user['id'])
            ->count();
            // Se deu liked true
            if($meLikes > 0) {
                $walls[$wallKey]['liked'] = true;
            }
            
        }

        $array['list'] = $walls;

        return $array;
    }

    public function like($id) {
        $array = ['error' => ''];
        // Pega usuário logado
        $user = auth()->user();

        $meLikes = WallLike::where('id_wall', $id)
        ->where('id_user', $user['id'])
        ->count();

        if($meLikes > 0) {
            // Remover o like
            WallLike::where('id_wall', $id)
            ->where('id_user', $user['id'])
            ->delete();
            $array['liked'] = false;
        } else {
            // Adicionar o like
            $newLike = new WallLike();
            $newLike->id_wall = $id;
            $newLike->id_user = $user['id'];
            $newLike->save();
            $array['liked'] = true;
        }
        // Refazer a contagem
        $array['likes'] = WallLike::where('id_wall', $id)
        ->count();

        return $array;
    }
}
