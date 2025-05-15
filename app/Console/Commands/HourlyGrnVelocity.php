<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use Illuminate\Support\Facades\Http;

use App\Models\ConsolidatedGrnData; 

use Carbon\Carbon;

use App\Models\HourlyGrn;

use App\Models\GrnOldData;

use Illuminate\Support\Facades\Log;


class HourlyGrnVelocity extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'SyncHourlyGrn';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command fetches hourly GRN velocity data';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        
       
       
        $now = Carbon::now()->subDay(02)->hour(15);
        // $now = Carbon::now();
        // dd($now);
         $from = Carbon::now()->subDay(2);
        //  dd($now);
         $payload= [
            'from' =>$from->format('Y-m-d 00:00:00'),
            'to' => $from->format('Y-m-d 23:59:59'),
            'client_id'=>"23"

         ];
        //  dd($payload);

         $response  = Http::withToken('WXdnegOkXHopVVzEWi2qW9qBfpluOUZ4WTttPgqV18rRk7ZcnN')
         ->post('https://uatreham.holisollogistics.com/api/cogneau/getGRN',$payload);
        //  dd($from, $to);
         $data = $response->json();
        //  dd($data);
        // $data=false;
        if(isset($data['data']) && is_array($data['data'])) {
            // dd($data);
            $remaininggrn=0;
            $totalgrnitems=0;
            $totalrejectedquantity=0;
            $totalokayQuantity =0;
            $totalgrn=0;
            $efficiency = 0;
        
            
            foreach($data['data'] as $item){
                // dd($item);
                // dump($item['created_at'], $now, $now->copy()->subHour());
                // dump(Carbon::parse($item['created_at'])->between($now->copy()->subHour(), $now));
                // dd();
                if (isset($item['created_at']) && Carbon::parse($item['created_at'])->between($now->copy()->subHour(), $now)) {
                    if($item['dtl_status']!=0)
                        {
                            $remaininggrn++;
                        }
                    $totalgrn ++;
                    $totalgrnitems += isset($item['total_grn_quantity']) ? (int)$item['total_grn_quantity'] : 0;
                    foreach ($item['grn_item'] as $i) {
                        $totalrejectedquantity += (int) $i['rejquan'];
                        $totalokayQuantity += (int) $i['okquan'];

                        // $totalQuantitySum +=(int) $i['totalquan'];

                        
                    }
                    
                }
                // dd($grn_done,$remaininggrn,$grndone,$totalgrnitems,$totalokayQuantity,$totalrejectedquantity);
                    # code...
            }

            $houlygrndone = $totalgrn - $remaininggrn;

            // variable which will store current time only not date  == 12:00:00

            $currentTime = Carbon::now()->format('H:00:00');
                    // dd($currentTime);
            $sixMonthAvg = ConsolidatedGrnData::whereRaw('TIME(time_slot) LIKE ?', ["%$currentTime%"])
            ->get();


            // dd(ConsolidatedGrnData::whereRaw('TIME(time_slot) LIKE ?', ["%$currentTime%"])->get());

            // dd($sixMonthAvg);
                $count=0;
                $GrnDone=0;
            foreach ($sixMonthAvg as $data) {
                // dd($data);
                // dump($data->grn_done);
                $data->total_grns >0 ? $count++:null;
                $GrnDone +=$data->grn_done;

                // dd($GrnDone,$data);
            }

        
            $avgGrnProcessed = $GrnDone/$count;
            //   dump([
            // 'GrnDone' => $GrnDone,
            // 'count' => $count,
            // 'avgGrnProcessed' => $avgGrnProcessed,
            // ]);
            $efficiency = $houlygrndone / $avgGrnProcessed *100;
                // dump($efficiency);
                // dd(  $remaininggrn,  $totalgrnitems,  $totalrejectedquantity,   $GrnDone,   $totalokayQuantity,  $totalgrn);
                ConsolidatedGrnData::create([
                    'time_slot'                => Carbon::now(),
                    'client_id'               => $item['Client_Id'] ,
                    'location_code'           => $item['location_code'] , 
                    'masterclient_id'         => $item['Master_Client_Id'] ?? 0, 
                    'total_grn_items'         => $item['total_grn_items'] ??0,
                    'total_okay_quantity'     => $totalokayQuantity ??0,
                    'total_rejected_quantity' => $item['totalrejectedquantity'] ??0,
                    'grn_done'                => $item['grn_done'] ?? 0,  
                    'created_at'              => now(),
                    'updated_at'              => now(),
                    'efficiency'              => $efficiency,
                    'total_grn'               => $totalgrn ??null,
                    
                ]);

        

                

        }
        else {
             Log::error('API request failed'.__CLASS__);
            return;
        }
    

    }
}

