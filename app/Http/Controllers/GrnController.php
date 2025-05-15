<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\ConsolidatedGrnData;
 
use Carbon\Carbon;
class GrnController extends Controller
{


    
    // function index(){
        // dd(ConsolidatedGrnData::all());
    //     return view('user');
    // }

    // function grndata(){
    //     return "list function called";
    // }



    // function grndata(){
    //     // condition to filter dada for currentdate
    //     return ConsolidatedGrnData::all();
    // }


    function grndata(Request $request)
    {
      

      $today = Carbon::today()->startofDay();
      $endofDay = Carbon::today()->endOfDay();

      $startofHour = Carbon::today()->hour(12)->minute(0)->second(0);
      $endofHour = Carbon::today()->hour(13)->minute(59)->second(59);
      $response = ConsolidatedGrnData::whereBetween('created_at',['2025-05-12 15:14:16','2025-05-12 16:14:16'])->get();
        //  dd($response);
        $totalQuantity =0;
        $remainingQuantity =0;
        $totalgrndone=0;
        $okQuantity = 0;
        $rejectedQuantity=0;
        $efficiencyAvg =0;
        $count=0;
        foreach ($response as $item) {
            $count++;
    $totalQuantity += $item['total_grn_items'];
    $remainingQuantity += $item['remaining_grn'];
    $okQuantity += $item['total_okay_quantity'];
    $totalgrndone += $item['grn_done'];
    $rejectedQuantity += $item['total_rejected_quantity'];
    $efficiencyAvg += $item['efficiency'];
       
    }
    // dd($efficiencyAvg/$count);
    //  dd([
    //         'Total Quantity' => $totalQuantity,
    //         'Remaining Quantity' => $remainingQuantity,
    //         'Total GRN Done' => $totalgrndone,
    //         'Total Okay Quantity' => $okQuantity,
    //         'Total Rejected Quantity' => $rejectedQuantity,
    //         'Efficiency' => $efficiencyAvg/$count,
    //     ]);
        
        $responseData = [
            'status'=>'success',
            'data'=> [
                    'total_quantity'=>$totalQuantity,
                    'remaining_quantity' => $remainingQuantity,
                    'total_grn_done' => $totalgrndone,
                    'total_okay_quantity' => $okQuantity,
                    'total_rejected_quantity' => $rejectedQuantity,
                    'average_efficiency' => round($efficiencyAvg/$count, 2),
              
            ]

                ];
       return $responseData;


    //    dd($response);


    }

}
