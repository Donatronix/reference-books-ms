<?php

namespace App\Console\Commands;

use App\Repositories\CurrencyRepository;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Console\Command;

/**
 *
 * @author Mauricio
 *
 */
class GetCurrencies extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'currencies:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command update the currencies rates';

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
     * @throws GuzzleException
     */
    public function handle()
    {
        $client = new Client(['base_uri' => 'https://openexchangerates.org']);
        $codes = CurrencyRepository::DEFAULT_CURRENCIES;
        $codes = array_merge($codes, CurrencyRepository::MINOR_CURRENCIES);

        $response = $client->get('/api/latest.json?app_id=' . env('OPEN_EXCHANGE_RATES') . '&symbols=' . implode(',', $codes));
        $rates = json_decode($response->getBody())->rates;
        $rates = get_object_vars($rates);
        $currencies = CurrencyRepository::getInstance()->findByCodes($codes);

        foreach ($currencies as $currency) {
            $rate = $rates[$currency->code];
            $currency->rate = $rate;
            $currency->save();
        }

        echo 'Finished currency update';
    }
}
