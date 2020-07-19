<?php

namespace App\Http\Controllers;

use App\Http\Repository\UserRepository;
use App\Http\Requests\User\UserUpdatePasswordRequest;
use Symfony\Component\HttpFoundation\Response;

class OptionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return view('option.index');
    }

    public function updatePassword(UserUpdatePasswordRequest $request, UserRepository $userRepository)
    {
        $user = $userRepository->updatePassword($request);
        return response()->json(['status' => true, 'message' => 'Password berhasil diupdate', 'user' => $user], Response::HTTP_OK);
    }
}
