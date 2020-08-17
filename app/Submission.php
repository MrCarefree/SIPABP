<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Submission extends Model
{
    public function programStudies()
    {
        return $this->belongsToMany('App\ProgramStudy')
            ->withPivot('siswa')
            ->withTimestamps();
    }

    public function submissionDetails()
    {
        return $this->hasMany('App\SubmissionDetail');
    }

    public function realizations()
    {
        return $this->hasMany('App\Realization');
    }
}
