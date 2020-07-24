<?php


namespace App\Http\Repository;


use App\User;
use Illuminate\Support\Facades\Auth;

class UserRepository
{
    public function getBesideMyself()
    {
        return User::notMyself()->get();
    }

    public function getProdiUser()
    {
        return User::prodi()->get();
    }

    public function create($userData)
    {
        $user = new User();
        $user->name = $userData->name;
        $user->email = $userData->email;
        $user->username = $userData->username;
        $user->password = $userData->password;
        $user->role = $userData->role;
        $user->save();

        return $user;
    }

    public function deleteUserById($id)
    {
        return User::findOrFail($id)->delete();
    }

    public function getUserById($id)
    {
        return User::findOrFail($id);
    }

    public function update($userData)
    {
        $user = User::findOrFail($userData->id);
        $user->name = $userData->name;
        $user->email = $userData->email;
        $user->username = $userData->username;
        if ($userData->filled('password')) $user->password = $userData->password;
        $user->role = $userData->role;
        $user->save();

        return $user;
    }

    public function updatePassword($userData)
    {
        $user = User::findOrFail(Auth::id());
        $user->password = $userData->new_password;
        $user->save();

        return $user;
    }
}
