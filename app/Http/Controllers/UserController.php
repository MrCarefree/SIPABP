<?php

namespace App\Http\Controllers;

use App\Http\Requests\User\UserStoreRequest;
use App\User;
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

    public function datatable()
    {
        Gate::authorize('access-menu');

        $users = User::notAdministrator()->get();
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

    public function store(UserStoreRequest $request)
    {
        $user = User::create($request->all());
        return response(['status' => true, 'message' => 'Tambah user berhasil', 'user' => $user], Response::HTTP_CREATED);
    }
}
