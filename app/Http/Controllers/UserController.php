<?php

namespace App\Http\Controllers;

use App\Http\Repository\UserRepository;
use App\Http\Requests\User\UserDeleteRequest;
use App\Http\Requests\User\UserGetRequest;
use App\Http\Requests\User\UserStoreRequest;
use App\Http\Requests\User\UserUpdateRequest;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        Gate::authorize('access-menu');

        return view('user.index');
    }

    public function datatable(UserRepository $userRepository)
    {
        Gate::authorize('access-menu');

        $users = $userRepository->getNotAdministrator();
        return DataTables::of($users)
            ->addColumn('action', function ($user) {
                return "
                    <a href=\"#\" class=\"btn btn-primary btn-sm btn_edit\" data-id=\"{$user->id}\"><i class=\"fas fa-edit\"></i></a>
                    <a href=\"#\" class=\"btn btn-danger btn-sm btn_delete\" data-id=\"{$user->id}\"><i class=\"fas fa-trash-alt\"></i></a>
                ";
            })
            ->editColumn('created_at', function ($user) {
                return $user->created_at->diffForHumans();
            })
            ->make(true);
    }

    public function store(UserStoreRequest $request, UserRepository $userRepository)
    {
        $user = $userRepository->create($request);
        return response()->json(['status' => true, 'message' => 'Tambah user berhasil', 'user' => $user], Response::HTTP_CREATED);
    }

    public function delete(UserDeleteRequest $request, UserRepository $userRepository)
    {
        $userRepository->deleteUserById($request->id);
        return response()->json(['status' => true, 'message' => 'User berhasil di hapus'], Response::HTTP_OK);
    }

    public function get(UserGetRequest $request, UserRepository $userRepository)
    {
        $user = $userRepository->getUserById($request->id);
        return response()->json(['status' => true, 'data' => $user], Response::HTTP_OK);
    }

    public function update(UserUpdateRequest $request, UserRepository $userRepository)
    {
        $userRepository->update($request);
        return response()->json(['status' => true, 'message' => 'User berhasil di update'], Response::HTTP_OK);
    }
}
