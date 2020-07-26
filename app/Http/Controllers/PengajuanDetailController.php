<?php

namespace App\Http\Controllers;

use App\Submission;

class PengajuanDetailController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
//        $this->pengajuanRepository = $pengajuanRepository;
    }

    public function index(Submission $id)
    {
        dd($id);
//        return view('dashboard.dashboard');
    }
}
