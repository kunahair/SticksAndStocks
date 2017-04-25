<?php

namespace App\Console\Commands;

use App\Stock;
use App\Transaction;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\User as User;
use App\StockHistory as History;
use phpDocumentor\Reflection\Types\Array_;
use Mail;
use App\Mail\StockChanged;


class stockChangeCheck extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'stockChange:check';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check for a change in a stock and mail all appropriate users';

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

        $users = User::all();
        $setPercentage = 2;

        foreach ($users as $user) {

            $buySell = Array();
            $emailArray = Array();

            foreach ($user->tradingAccounts()->get() as $tradeAccount) {
                foreach ($tradeAccount->transactions()->get() as $transaction) {
                    $buySell[$transaction->stock()->first()->id] = 0;
                }
                foreach ($tradeAccount->transactions()->get() as $transaction) {
                    $buySell[$transaction->stock()->first()->id] += $transaction->bought;
                    $buySell[$transaction->stock()->first()->id] -= $transaction->sold;
                }
                foreach ($buySell as $key => $value) {
                    // Check if the user has the stock
                    if ($value > 0) {
                        $stock_name = Stock::where('id', $key)->first()->stock_name;
                        $stock_symbol = Stock::where('id', $key)->first()->stock_symbol;
                        // Current Value
                        $currentPrice = Stock::where('id', $key)->first()->current_price;
                        // Buy Value
                        $buyPrice = Transaction::where('stock_id', $key)->where('trade_account_id', $tradeAccount->id)->orderBy('id', 'desc')->first()->price;
                        // Growth Percentage
                        $growth = ($currentPrice - $buyPrice) / $buyPrice * 100;
                        // If growth is greater than percentage, add to email array
                        if ($growth >= $setPercentage || $growth <= -1 * $setPercentage) {
                            array_push($emailArray, array("trading_account" => $tradeAccount->name,"stock_name" => $stock_name, "stock_symbol" => $stock_symbol, "growth" => $growth . "%"));
                        }
                    }
                }
            }

            print_r($emailArray);

            if (count($emailArray) > 0) {
                $content = [
                    'title' => 'Your stocks are starting to change significantly.',
                    'info' => $emailArray,
                    'name' => $user->name
                ];

                Mail::to($user->email)->send(new StockChanged($content));
            }

        }

    }
}
