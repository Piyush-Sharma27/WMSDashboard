<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Hoursley extends Model
{
    //
  

        protected  $fillable = [
            'timeslot', 'client_id', 'location_code', 'masterclient_id',
            'remaining_grn', 'total_grn_items', 'total_okay_quantity',
            'total_rejected_quantity', 'grn_done', 'total_grn', 'efficiency'
    ];
    
}
