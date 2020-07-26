<?php


namespace App\Http\Repository;


use App\Submission;
use Illuminate\Support\Facades\Auth;

class PengajuanRepository
{

    public function getPengajuanByUserProdi()
    {
        $programStudies = Auth::user()->programStudies()->get();
        $submissions = Submission::with(['programStudies' => function ($query) use ($programStudies) {
            $query->find($programStudies);
        }])->latest();
        return $submissions;
    }

    public function create($pengajuanData)
    {
        if (!$this->checkSemester($pengajuanData)) {
            return false;
        }
        $pengajuan = new Submission();
        $pengajuan->tahun_akademik = $pengajuanData->tahun_akademik;
        $pengajuan->semester = $pengajuanData->semester;
        $pengajuan->save();

        $this->syncProdi($pengajuan, $pengajuanData);
        $programStudies = $pengajuan->programStudies()->get();
        $this->updateSiswaAndPagu($pengajuan, $programStudies);

        return $pengajuan;
    }

    public function checkSemester($pengajuanData)
    {
        $siswaArr = explode(',', $pengajuanData->siswa);
        $prodiArr = $pengajuanData->prodi;
        if (count($siswaArr) == count($prodiArr)) {
            return true;
        } else {
            return false;
        }
    }

    public function syncProdi(Submission $submission, $pengajuanData)
    {
        $submission->programStudies()->detach();

        $siswaArr = explode(',', $pengajuanData->siswa);
        $prodiArr = $pengajuanData->prodi;
        for ($x = 0; $x < count($prodiArr); $x++) {
            $submission->programStudies()->attach($prodiArr[$x], ['siswa' => $siswaArr[$x]]);
        }
    }

    public function updateSiswaAndPagu(Submission $submission, $programStudies)
    {
        $totalSiswa = 0;
        $totalPagu = 0;
        foreach ($programStudies as $programStudy) {
            $totalSiswa += $programStudy->pivot->siswa;
            $totalPagu += $programStudy->pagu * $programStudy->pivot->siswa;
        }

        $submission->siswa = $totalSiswa;
        $submission->pagu = $totalPagu;
        $submission->save();
    }

    public function deletePengajuanById($id)
    {
        return Submission::findOrFail($id)->delete();
    }

    public function getPengajuanById($id)
    {
        return Submission::with('programStudies')->findOrFail($id);
    }

    public function update($pengajuanData)
    {
        if (!$this->checkSemester($pengajuanData)) {
            return false;
        }

        $pengajuan = Submission::findOrFail($pengajuanData->id);
        $pengajuan->tahun_akademik = $pengajuanData->tahun_akademik;
        $pengajuan->semester = $pengajuanData->semester;
        $pengajuan->save();

        $this->syncProdi($pengajuan, $pengajuanData);
        $programStudies = $pengajuan->programStudies()->get();
        $this->updateSiswaAndPagu($pengajuan, $programStudies);

        return $pengajuan;
    }
}
