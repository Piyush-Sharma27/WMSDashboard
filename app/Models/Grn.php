<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Grn extends Model
{
    //

    protected $fillable = [
        'remaining_grn',
        'total_grn_items',
        'total_okay_quantity',
        'total_rejected_quantity',
        'grn_done',
        'efficiency',
        'total_grn',
        
    ];

}
