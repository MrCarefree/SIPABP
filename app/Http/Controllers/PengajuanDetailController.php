<?php

namespace App\Http\Controllers;

use App\Http\Repository\PengajuanDetailRepository;
use App\Http\Requests\Pengajuan_detail\PengajuanDetailDeleteRequest;
use App\Http\Requests\Pengajuan_detail\PengajuanDetailGetRequest;
use App\Http\Requests\Pengajuan_detail\PengajuanDetailStoreRequest;
use App\Http\Requests\Pengajuan_detail\PengajuanDetailUpdateNegotiationRequest;
use App\Http\Requests\Pengajuan_detail\PengajuanDetailUpdateRequest;
use App\Submission;
use Exception;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use NumberFormatter;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class PengajuanDetailController extends Controller
{
    /**
     * @var PengajuanDetailRepository
     */
    private $detailRepository;

    /**
     * PengajuanDetailController constructor.
     * @param PengajuanDetailRepository $detailRepository
     */
    public function __construct(PengajuanDetailRepository $detailRepository)
    {
        $this->middleware('auth');
        $this->detailRepository = $detailRepository;
    }

    /**
     * @param Submission $id
     * @return Factory|View
     */
    public function index(Submission $id)
    {
        return view('pengajuan_detail.index', ['pengajuan' => $id]);
    }

    /**
     * @param Submission $id
     * @return mixed
     * @throws Exception
     */
    public function datatable(Submission $id)
    {
        $pengajuanDetails = $this->detailRepository->getBySubmission($id);
        return DataTables::of($pengajuanDetails)
            ->addColumn('action', function ($detail) use ($id) {
                if (empty($detail->negotiation)) {
                    if (Auth::user()->role == 'prodi' && ($id->status == 1 || $id->status == 3)) {
                        return "
                            <a href=\"#\" class=\"btn btn-warning btn-sm btn_edit\" title='Edit' data-id=\"{$detail->id}\"><i class=\"fas fa-edit\"></i></a>
                            <a href=\"#\" class=\"btn btn-danger btn-sm btn_delete\" title='Delete' data-id=\"{$detail->id}\"><i class=\"fas fa-trash-alt\"></i></a>
                        ";
                    } elseif (Auth::user()->role != 'prodi' && $id->status == 2) {
                        return "
                            <a href=\"#\" class=\"btn btn-warning btn-sm btn_change\" title='Change Status' data-id=\"{$detail->id}\"><i class=\"fas fa-edit\"></i></a>
                        ";
                    }
                }

                return '';
            })
            ->editColumn('harga_satuan', function ($detail) {
                $fmt = new NumberFormatter('id_ID', NumberFormatter::CURRENCY);
                return empty($detail->negotiation) ? $fmt->format($detail->harga_satuan) : $fmt->format($detail->negotiation->harga_satuan);

            })
            ->editColumn('harga_total', function ($detail) {
                $fmt = new NumberFormatter('id_ID', NumberFormatter::CURRENCY);
                return empty($detail->negotiation) ? $fmt->format($detail->harga_total) : $fmt->format($detail->negotiation->harga_total);
            })
            ->editColumn('jumlah', function ($detail) {
                return empty($detail->negotiation) ? $detail->jumlah : $detail->negotiation->jumlah;
            })
            ->editColumn('keterangan', function ($detail) {
                return empty($detail->negotiation) ? $detail->keterangan : $detail->negotiation->keterangan;
            })
            ->editColumn('nama_barang', function ($detail) {
                return ucfirst($detail->nama_barang);
            })
            ->editColumn('created_at', function ($detail) {
                return $detail->created_at->format('m/d/Y');
            })
            ->editColumn('image_path', function ($submission) {
                $imgUrl = asset('storage' . str_replace('public', '', $submission->image_path));
                return "<img src=\"{$imgUrl}\" alt=\"Gambar Barang\" width=\"250\" />";
            })
            ->rawColumns(['image_path', 'action'])
            ->make(true);
    }

    /**
     * @param PengajuanDetailStoreRequest $request
     * @param $id
     * @return JsonResponse
     */
    public function store(PengajuanDetailStoreRequest $request, Submission $id)
    {
        $detail = $this->detailRepository->create($request, $id);
        if ($detail['status'])
            return response()->json(['status' => true, 'message' => 'Success adding detail', 'data' => $detail['data']], Response::HTTP_CREATED);
        else
            return response()->json(['status' => false, 'message' => $detail['message']], Response::HTTP_BAD_REQUEST);
    }

    /**
     * @param PengajuanDetailDeleteRequest $request
     * @param $id
     * @return JsonResponse
     */
    public function delete(PengajuanDetailDeleteRequest $request, $id)
    {
        $this->detailRepository->deleteById($request->id);
        return response()->json(['status' => true, 'message' => 'Success deleting detail'], Response::HTTP_OK);
    }

    /**
     * @param PengajuanDetailGetRequest $request
     * @param $id
     * @return JsonResponse
     */
    public function get(PengajuanDetailGetRequest $request, $id)
    {
        $detail = $this->detailRepository->getById($request->id);
        return response()->json(['status' => true, 'data' => $detail], Response::HTTP_OK);
    }

    /**
     * @param PengajuanDetailUpdateRequest $request
     * @param $id
     * @return JsonResponse
     */
    public function update(PengajuanDetailUpdateRequest $request, Submission $id)
    {
        $detail = $this->detailRepository->update($request, $id);
        if ($detail['status'])
            return response()->json(['status' => true, 'message' => 'Success updating detail', 'data' => $detail['data']], Response::HTTP_OK);
        else
            return response()->json(['status' => false, 'message' => $detail['message']], Response::HTTP_BAD_REQUEST);
    }

    public function updateNegotiation(PengajuanDetailUpdateNegotiationRequest $request, Submission $id)
    {
        $detail = $this->detailRepository->updateNegotiation($request, $id);
        if ($detail['status'])
            return response()->json(['status' => true, 'message' => 'Success update detail', 'data' => $detail['data']], Response::HTTP_OK);
        else
            return response()->json(['status' => false, 'message' => $detail['message']], Response::HTTP_BAD_REQUEST);
    }

    /**
     * @param Submission $id
     * @return RedirectResponse
     */
    public function negosiasi(Submission $id)
    {
        if ($id->submissionDetails()->exists()) {
            $id->status = 2;
            $id->save();

            return redirect()->back()->with("status", ["status" => true, "message" => "Success updated submission"]);
        }

        return redirect()->back()->with("status", ["status" => false, "message" => "Please insert mininum 1 item"]);
    }

    /**
     * @param Submission $id
     * @return RedirectResponse
     */
    public function realisasi(Submission $id)
    {
        $submissionDetail = $id->submissionDetails()->doesntHave('negotiation')->first();
        if (empty($submissionDetail)) {
            $id->status = 4;
            $id->save();

            return redirect()->back()->with("status", ["status" => true, "message" => "Success updated submission"]);
        }
        return redirect()->back()->with("status", ["status" => false, "message" => "Please check all items"]);
    }

    public function pengajuan(Submission $id)
    {
        $id->status = 3;
        $id->save();

        return redirect()->back();
    }
}
