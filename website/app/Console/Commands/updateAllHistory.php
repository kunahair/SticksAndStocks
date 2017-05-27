<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Stock as Stock;

class updateAllHistory extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'company:updateAllHistory';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Updates the history of all stocks';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $getAllCompanies = new \GetAllCompanies;
//        $getAllCompanies->getCompanies();
        $getAllCompanies->getASXStocks();
        // Do 200 stocks at a time to save memory
//        Stock::chunk(200, function($stocks) {
//            $number = 1;
//            foreach ($stocks as $stock) {
//                print($number . "\t" . $stock->stock_name . "\t" . $stock->stock_symbol . "\n");
//                $this->call('company:get', [
//                            'code' => $stock->stock_symbol
//                        ]);
//                $number++;
//            }
//        });


    }
}
