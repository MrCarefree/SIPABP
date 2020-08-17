<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Realization extends Model
{
    public function submission()
    {
        return $this->belongsTo('App\Submission');
    }

    public function submissionDetail()
    {
        return $this->belongsTo('App\SubmissionDetail');
    }
}
