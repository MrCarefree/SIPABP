<?php

namespace App\Http\Controllers;

use App\Http\Repository\PengajuanRepository;
use App\Http\Repository\ProdiRepository;
use App\Http\Requests\Pengajuan\PengajuanDeleteRequest;
use App\Http\Requests\Pengajuan\PengajuanGetRequest;
use App\Http\Requests\Pengajuan\PengajuanStoreRequest;
use App\Http\Requests\Pengajuan\PengajuanUpdateRequest;
use NumberFormatter;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class PengajuanController extends Controller
{
    /**
     * @var PengajuanRepository
     */
    private $pengajuanRepository;

    public function __construct(PengajuanRepository $pengajuanRepository)
    {
        $this->middleware('auth');
        $this->pengajuanRepository = $pengajuanRepository;
    }

    public function index(ProdiRepository $prodiRepository)
    {
        $programStudies = $prodiRepository->getProdiByUser();
        return view('pengajuan.index', ['programStudies' => $programStudies]);
    }

    public function datatable()
    {
        $submissions = $this->pengajuanRepository->getPengajuanByUserProdi();
        return DataTables::of($submissions)
            ->addColumn('action', function ($submission) {
                if ($submission->status == 1) {
                    return "
                        <a href=\"#\" class=\"btn btn-warning btn-sm btn_edit\" title='Edit' data-id=\"{$submission->id}\"><i class=\"fas fa-edit\"></i></a>
                        <a href=\"#\" class=\"btn btn-danger btn-sm btn_delete\" title='Delete' data-id=\"{$submission->id}\"><i class=\"fas fa-trash-alt\"></i></a>
                    ";
                }

                return '';
            })
            ->editColumn('pagu', function ($submission) {
                $fmt = new NumberFormatter('id_ID', NumberFormatter::CURRENCY);
                return $fmt->format($submission->pagu);
            })
            ->editColumn('status', function ($submission) {
                return $submission->status == 1 ? 'Diajukan' : ($submission->status == 2 ? 'Negosiasi' : 'Realisasi');
            })
            ->editColumn('created_at', function ($submission) {
                return $submission->created_at->diffForHumans();
            })
            ->editColumn('program_studies', function ($submission) {
                return optional($submission->programStudies)->implode('nama_prodi', ', ');
            })
            ->make(true);
    }

    public function store(PengajuanStoreRequest $request)
    {
        $pengajuan = $this->pengajuanRepository->create($request);
        if ($pengajuan)
            return response()->json(['status' => true, 'message' => 'Pengajuan berhasil dibuat', 'redirect' => route('pengajuan.detail', ['id' => $pengajuan])]);
        else
            return response()->json(['status' => false, 'message' => 'Something has gone wrong, please check your form and try again'], Response::HTTP_BAD_REQUEST);
    }

    public function delete(PengajuanDeleteRequest $request)
    {
        $this->pengajuanRepository->deletePengajuanById($request->id);
        return response()->json(['status' => true, 'message' => 'Pengajuan berhasil di hapus'], Response::HTTP_OK);
    }

    public function get(PengajuanGetRequest $request)
    {
        $pengajuan = $this->pengajuanRepository->getPengajuanById($request->id);
        return response()->json(['status' => true, 'pengajuan' => $pengajuan], Response::HTTP_OK);
    }

    public function update(PengajuanUpdateRequest $request)
    {
        $pengajuan = $this->pengajuanRepository->update($request);
        if ($pengajuan)
            return response()->json(['status' => true, 'message' => 'Pengajuan berhasil di update'], Response::HTTP_OK);
        else
            return response()->json(['status' => false, 'message' => 'Something has gone wrong, please check your form and try again'], Response::HTTP_BAD_REQUEST);
    }
}
