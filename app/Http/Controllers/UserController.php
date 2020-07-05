<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Support\Facades\Gate;
use Yajra\DataTables\Facades\DataTables;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(){
        Gate::authorize('access-menu');

        return view('user.index');
    }

    public function datatable(){
        Gate::authorize('access-menu');

//        $users = User::notAdministrator()->get();
        $users = User::all();
        return DataTables::of($users)
            ->addColumn('action', function ($user){
                return "
                    <a href=\"#\" class=\"btn btn-primary btn-sm\" id=\"btn_edit\"><i class=\"fas fa-edit\"></i></a>
                    <a href=\"#\" class=\"btn btn-danger btn-sm\" id=\"btn_delete\"><i class=\"fas fa-trash-alt\"></i></a>
                ";
            })
            ->editColumn('created_at', function($user){
                return $user->created_at->diffForHumans();
            })
            ->make(true);
    }
}
