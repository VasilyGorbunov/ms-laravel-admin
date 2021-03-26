<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateInfoRequest;
use App\Http\Requests\UpdatePasswordRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Requests\UserCreateRequest;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
  public function index()
  {
    return User::paginate();
  }

  public function show($id)
  {
    return User::find($id);
  }

  public function store(UserCreateRequest $request)
  {
    $user = User::create($request->only('first_name', 'last_name', 'email') + [
      'password' => Hash::make($request->input('password')),
    ]);

    return response($user, Response::HTTP_CREATED);
  }

  public function update(Request $request, $id)
  {
    $user = User::find($id);
    $user->update($request->only('first_name', 'last_name', 'email'));

    return response($user, Response::HTTP_ACCEPTED);
  }

  public function destroy($id)
  {
    User::destroy($id);

    return response(null, Response::HTTP_NO_CONTENT);
  }

  public function user()
  {
    return Auth::user();
  }

  public function updateInfo(UpdateInfoRequest $request)
  {
    $user = Auth::user();
    $user->update($request->only('first_name', 'last_name', 'email'));

    return response($user, Response::HTTP_ACCEPTED);
  }

  public function updatePassword(UpdatePasswordRequest $request)
  {
    $user = Auth::user();
    $user->update([
      'password' => Hash::make($request->input('password'))
    ]);

    return response($user, Response::HTTP_ACCEPTED);
  }
}
