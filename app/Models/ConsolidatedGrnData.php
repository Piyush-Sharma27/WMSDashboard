<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ConsolidatedGrnData extends Model
{
    protected $table = 'consolidated_grn_data';

    protected $fillable = [
        'time_slot', 'client_id', 'location_code', 'masterclient_id',
        'total_quantity', 'remaining_grn', 'total_okay_quantity',
        'total_rejected_quantity', 'grn_processed', 'grn_done',
        'total_grns', 'total_grn_items', 'efficiency', 
    ];
    
}

// dd(ConsolidatedGrnData::all());

