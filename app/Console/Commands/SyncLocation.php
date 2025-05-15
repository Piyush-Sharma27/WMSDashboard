<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use App\Models\Location;
// fetch client locations
class synclocation extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'SyncLocation';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command is bringing the data to the table';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        //
        $response = Http::withToken('WXdnegOkXHopVVzEWi2qW9qBfpluOUZ4WTttPgqV18rRk7ZcnN')
                ->get('https://uatreham.holisollogistics.com/api/cogneau/getClient');

        
        if($response->successful()){
            $data = $response->json();
        }   
        else{
            Log::error('API request failed '. __CLASS__);
            return;
        }
        $uniquecities = collect($data['data'])->pluck('Client_City')->unique()->values();

        
        // dd($uniquecities);
        foreach($uniquecities as $city){
            // dd($city);
            Location::updateOrCreate(
                ['name' =>$city],
                
            );
        }

       
    }
}
