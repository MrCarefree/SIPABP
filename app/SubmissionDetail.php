<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SubmissionDetail extends Model
{
    public function submission()
    {
        return $this->belongsTo('App\Submission');
    }

    public function negotiation()
    {
        return $this->hasOne('App\Negotiation');
    }
}
