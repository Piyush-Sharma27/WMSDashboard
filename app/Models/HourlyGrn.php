<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HourlyGrn extends Model
{
    //
    protected $table ='hourly_grns';
    protected $fillable =[

        'timeslot','client_id','location_code','mastercleint_id','remaining_grn','total_grn_items','total_rejected_quantity','grn_done','efficiency','total_grn'

    ];
}
