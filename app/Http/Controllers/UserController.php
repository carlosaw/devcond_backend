<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
  // Pega todos os usuários
  public function getAll() {
    $array = ['error' => '', 'list' =>[]];
    $user = auth()->user();
    $users = User::all();
    $array['list'] = $users;
    return $array;
  }

  // Pega informações de um usuário
  public function getInfo($id) {
    $array = ['error' => ''];
    $users = User::find($id);
    if($users) {
      $users = User::where('id', $id)->get();
      foreach($users as $uKey => $uValue) {
        $users[$uKey]['name'] = $uValue['name'];
      }
      $array['users'] = $users;
    } else {
      $array['error'] = 'Usuário inexistente!';
      return $array;
    }   
    return $array;
  }

  // Modificar informações de um usuário
  public function update($id, Request $request) {
    $array = ['error' => ''];

    $userName = $request->input('name');
    //$userPass = $request->input('password');

    $newUserName = User::find($id);

    if(!empty($userName)) {
      $newUserName->name = $userName;
      $newUserName->save();
    } else {
      $array['error'] = 'Usuário inexistente!';
      return $array;
    }

    return $array;
  }

}