<?php

namespace App\Console\Commands;

use App\Models\Grn;
use Carbon\Carbon;
use Illuminate\Console\Command;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SyncGRNData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'SyncGrn';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command is bringing the 6 months of the grn data';

    /**
     * Execute the console command.
     */
    public function handle()
    {

        //

        $payload = [
           
            "from" => "2024-10-23 00:00:00",
            "to" => "2025-04-23",
            "client_id" => "23"
        ];


        $response = Http::withToken('WXdnegOkXHopVVzEWi2qW9qBfpluOUZ4WTttPgqV18rRk7ZcnN')
        ->post('https://uatreham.holisollogistics.com/api/cogneau/getGRN',$payload);
        
        $date = Carbon::now();
        // dd($response);
        if($response->successful()){
            $data = $response->json();
            // dd($data);
        }
        else{
            Log::error('API request failed'.__CLASS__);
            return;
        }
        
        $totalgrn = 0;
        $remanininggrn=0;
        $totalgrnitems = 0;
        $totalokayquantity = 0;
        $totalrejectedquantity = 0;
        $grndone=0;
        $efficiency =0;

     

        foreach ($data['data'] as $key) {
            

            $grnno = $key['grnno'];
            $totalgrnquantity = $key['total_grn_quantity'];
            $okayquantity = 0;
            $rejectedquantity = 0;
            if($key['dtl_status']!=0)
            {
                $remanininggrn++;
            }



            foreach ($key['grn_item'] as $qut) {
                $okayquantity += (int) $qut['okquan'];
                $rejectedquantity += (int) $qut['rejquan'];
            }

            $totalgrn++;
            $totalgrnitems += $totalgrnquantity;
            $totalokayquantity += $okayquantity;
            $totalrejectedquantity+= $rejectedquantity;

           $grndone = $totalgrn - $remanininggrn; 
        }

        if($totalgrn >0){
            $efficiency = ($grndone / $totalgrn) *100;
        }
        else
        {   
            $efficiency=0;

        }

        // dd($totalgrn, $totalgrnitems, $totalokayquantity,$totalrejectedquantity,$remanininggrn,$grndone,$efficiency);


        Grn::create([
            'remaining_grn' => $remanininggrn,
            'total_grn_items' => $totalgrnitems,
            'total_okay_quantity' => $totalokayquantity,
            'total_rejected_quantity' => $totalrejectedquantity,
            'grn_done' => $grndone,
            'efficiency' => round($efficiency),
            'total_grn'=> $totalgrn,
        ]);
    }
}

