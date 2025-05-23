<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\ConsolidatedGrnData; 



class GrnoldData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'GrnOldDatahourly';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command is bringing the 6 months old data hourly and date wise';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        //
       $now = Carbon::parse("2025-05-20 11:58:14");
        // dd( $now = Carbon::now());
        $from = $now->copy()->subMonths(2)->startOfHour();
        // dd($now->copy()->subMonths(6)->startOfHour());
        
       
       
       
       
       
       
        //whereBetweenDate
        //reasons: date format, if saved as string convert to datetime
        // dd($to);

        $payload = [
            "from" => $from,
            "to" => $now,
            "client_id" => "23"
        ];
        // dd($payload);

        $response = Http::withToken('WXdnegOkXHopVVzEWi2qW9qBfpluOUZ4WTttPgqV18rRk7ZcnN')
        ->post('https://uatreham.holisollogistics.com/api/cogneau/getGRN',$payload);

        $data = $response->json();
        // dd($data);

            
        if($response->successful()){
            $responseData = $response->json();
            // dd($responseData);
        }
        else{
            Log::error('API request failed'.__CLASS__);
            return;
        }

        $now = Carbon::now();
        // dd( $now = Carbon::now());
        // $from = $now->copy()->subMonths(6)->startOfDay(); 
        // dd( $from = $now->copy()->subMonths(6)->startOfDay());
        $to = $now->copy()->endOfDay();
        // dd( $to = $now->copy()->endOfDay());
        
        
        $currentDay = $from;
        // dd( $currentDay = $from);
        
        while ($currentDay->lte($to)) {
            $dayStart = $currentDay->copy()->startOfDay(); 
            //   dd($dayStart);

            // dd($dayStart = $currentDay->copy()->startOfDay());
            $dayEnd = $currentDay->copy()->endOfDay(); 
            // dd($dayEnd = $currentDay->copy()->endOfDay());
      
            $currentHour = $dayStart; 
            
            while ($currentHour->lte($dayEnd)) {
                $totalGrn=0;
                $totalGrnQuantity = 0;
                $totalRejectedQuantity = 0;
                $totalOkayQuantity = 0;
                $totalProcessedGrns = 0;
                $remanininggrn=0;
                $totalgrnitems=0;
        
                foreach ($responseData['data'] as $grn) {
                  $grnDate = Carbon::parse($grn['created_at']); 
                
                // dd($data);
                //   dd($responseData);
                // dd($grn);
                    if ($grnDate->between($currentHour, $currentHour->copy()->addHour())) {
                      
                        // dd($currentHour);


                        $totalGrnQuantity += (int) ($grn['total_grn_quantity'] ?? 0);
                        $totalGrn++;
                        foreach ($grn['grn_item'] as $item) {
                            $totalRejectedQuantity += (int) $item['rejquan'];
                            $totalOkayQuantity += (int) $item['okquan'];
                            $totalgrnitems += $item['totquan'];
                            
                        }
                        if($grn['dtl_status']!=0)
                        {
                            $remanininggrn++;
                        }
                    }
                }
        
              
                ConsolidatedGrnData::create([
                    'time_slot'                => $currentHour,
                    'client_id'               => $grn['Client_Id'], 
                    'location_code'           => $grn['location_code'], 
                    'masterclient_id'         => $grn['Master_Client_Id'],
                    'total_grn_items'         => $totalgrnitems ??0,
                    'remaining_grn'           => $remanininggrn>0?$remanininggrn: 0,
                    'total_okay_quantity'     => $totalOkayQuantity>0?$totalOkayQuantity: 0,
                    'total_rejected_quantity' => $totalRejectedQuantity>0?$totalRejectedQuantity: 0,
                    'grn_done'                => $totalGrn - $remanininggrn, 
                    'total_grns'              => $totalGrn,
                    'created_at'              => now(),
                    'updated_at'              => now(),
                    'efficiency'              => 0
                ]);
                // dump( $totalGrnQuantity,$totalOkayQuantity,$remanininggrn);
               
                $currentHour = $currentHour->addHour();
            }
           
            $currentDay = $currentDay->addDay();
         }
    }

       
}
