<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GrnOldData extends Model
{
    //
    protected $table ='grn_old_data';
    protected $fillable = [
        'grnno', 'timeslot', 'client_id', 'location_code','masterclient_id','total_quantity','remaining_grn','total_okay_quantity','total_rejected_quantity','grn_processed','grn_done',
        'total_grns'
    ];
}
