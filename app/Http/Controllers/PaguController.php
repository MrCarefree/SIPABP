<?php

namespace App\Http\Controllers;

use App\Http\Repository\UserRepository;
use App\Http\Requests\User\UserGetRequest;
use App\Http\Requests\User\UserUpdatePaguRequest;
use Illuminate\Support\Facades\Gate;
use NumberFormatter;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class PaguController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        Gate::authorize('access-menu', 'wakil_direktur');

        return view('pagu.index');
    }

    public function datatable(UserRepository $userRepository)
    {
        Gate::authorize('access-menu', 'wakil_direktur');

        $users = $userRepository->getProdi();
        return DataTables::of($users)
            ->addColumn('action', function ($user) {
                return "
                    <a href=\"#\" class=\"btn btn-primary btn-sm btn_edit\" data-id=\"{$user->id}\"><i class=\"fas fa-edit\"></i></a>
                ";
            })
            ->editColumn('pagu', function ($user) {
                $fmt = new NumberFormatter('id_ID', NumberFormatter::CURRENCY);
                return $fmt->formatCurrency($user->pagu, 'IDR');
            })
            ->make(true);
    }

    public function get(UserGetRequest $request, UserRepository $userRepository)
    {
        $user = $userRepository->getUserById($request->id);
        return response()->json(['status' => true, 'data' => $user], Response::HTTP_OK);
    }

    public function update(UserUpdatePaguRequest $request, UserRepository $userRepository)
    {
        $user = $userRepository->updatePagu($request);
        return response()->json(['status' => true, 'message' => 'Pagu berhasil diupdate', 'user' => $user], Response::HTTP_OK);
    }
}
