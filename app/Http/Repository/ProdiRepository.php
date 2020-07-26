<?php


namespace App\Http\Repository;

use App\ProgramStudy;
use Illuminate\Support\Facades\Auth;

class ProdiRepository
{
    public function create($prodiData)
    {
        $programStudy = new ProgramStudy();
        $programStudy->kode_prodi = $prodiData->kode_prodi;
        $programStudy->nama_prodi = $prodiData->nama_prodi;
        $programStudy->pagu = $prodiData->pagu;
        $programStudy->save();

        return $programStudy;
    }

    public function deleteProdiById($id)
    {
        return ProgramStudy::findOrFail($id)->delete();
    }

    public function getProdiById($id)
    {
        return ProgramStudy::findOrFail($id);
    }

    public function update($prodiData)
    {
        $prodi = ProgramStudy::findOrFail($prodiData->id);
        $prodi->kode_prodi = $prodiData->kode_prodi;
        $prodi->nama_prodi = $prodiData->nama_prodi;
        $prodi->pagu = $prodiData->pagu;
        $prodi->save();

        return $prodi;
    }

    public function updateKaprodi($prodiData)
    {
        $prodi = ProgramStudy::findOrFail($prodiData->id);
        $prodi->user_id = $prodiData->user;
        $prodi->save();

        return $prodi;
    }

    public function getProdiByUser()
    {
        return Auth::user()->programStudies()->get();
    }
}
