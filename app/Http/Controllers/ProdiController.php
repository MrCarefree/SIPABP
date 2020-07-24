<?php

namespace App\Http\Controllers;

use App\Http\Repository\ProdiRepository;
use App\Http\Repository\UserRepository;
use App\Http\Requests\Prodi\ProdiDeleteRequest;
use App\Http\Requests\Prodi\ProdiGetRequest;
use App\Http\Requests\Prodi\ProdiStoreRequest;
use App\Http\Requests\Prodi\ProdiUpdateKaprodiRequest;
use App\Http\Requests\Prodi\ProdiUpdateRequest;
use App\ProgramStudy;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class ProdiController extends Controller
{
    private $prodiRepository;

    public function __construct(ProdiRepository $prodiRepository)
    {
        $this->middleware('auth');
        $this->prodiRepository = $prodiRepository;
    }

    public function index(UserRepository $userRepository)
    {
        Gate::authorize('access-menu', 'wakil_direktur');
        $users = $userRepository->getProdiUser();

        return view('prodi.index', ['users' => $users]);
    }

    public function datatable()
    {
        $programStudies = ProgramStudy::with('user')->get();
        return DataTables::of($programStudies)
            ->addColumn('action', function ($programStudies) {
                return "
                    <a href=\"#\" class=\"btn btn-primary btn-sm btn_kaprodi\" title='Tunjuk Kaprodi' data-id=\"{$programStudies->id}\"><i class=\"fas fa-user\"></i></a>
                    <a href=\"#\" class=\"btn btn-warning btn-sm btn_edit\" title='Edit' data-id=\"{$programStudies->id}\"><i class=\"fas fa-edit\"></i></a>
                    <a href=\"#\" class=\"btn btn-danger btn-sm btn_delete\" title='Delete' data-id=\"{$programStudies->id}\"><i class=\"fas fa-trash-alt\"></i></a>
                ";
            })
            ->editColumn('kode_prodi', function ($programStudies) {
                return strtoupper($programStudies->kode_prodi);
            })
            ->editColumn('nama_prodi', function ($programStudies) {
                return ucwords($programStudies->nama_prodi);
            })
            ->editColumn('created_at', function ($programStudies) {
                return $programStudies->created_at->diffForHumans();
            })
            ->make(true);
    }

    public function store(ProdiStoreRequest $request)
    {
        $prodi = $this->prodiRepository->create($request);
        return response()->json(['status' => true, 'message' => 'Tambah prodi berhasil', 'prodi' => $prodi], Response::HTTP_CREATED);
    }

    public function delete(ProdiDeleteRequest $request)
    {
        $this->prodiRepository->deleteProdiById($request->id);
        return response()->json(['status' => true, 'message' => 'Prodi berhasil di hapus'], Response::HTTP_OK);
    }

    public function get(ProdiGetRequest $request)
    {
        $prodi = $this->prodiRepository->getProdiById($request->id);
        return response()->json(['status' => true, 'prodi' => $prodi], Response::HTTP_OK);
    }

    public function update(ProdiUpdateRequest $request)
    {
        $this->prodiRepository->update($request);
        return response()->json(['status' => true, 'message' => 'Prodi berhasil di update'], Response::HTTP_OK);
    }

    public function updateKaprodi(ProdiUpdateKaprodiRequest $request)
    {
        $this->prodiRepository->updateKaprodi($request);
        return response()->json(['status' => true, 'message' => 'Prodi kaprodi berhasil di update'], Response::HTTP_OK);
    }
}
