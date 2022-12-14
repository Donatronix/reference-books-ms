<?php

namespace App\Console\Commands;

use App\Models\ExchangeRate;
use App\Repositories\CurrencyRepository;
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

    private array $currencyCodes = [
        'USD' => 2781,
        'ALL' => 3526,
        'DZD' => 3537,
        'ARS' => 2821,
        'AMD' => 3527,
        'AUD' => 2782,
        'AZN' => 3528,
        'BHD' => 3531,
        'BDT' => 3530,
        'BYN' => 3533,
        'BMD' => 3532,
        'BOB' => 2832,
        'BAM' => 3529,
        'BRL' => 2783,
        'BGN' => 2814,
        'KHR' => 3549,
        'CAD' => 2784,
        'CLP' => 2786,
        'CNY' => 2787,
        'COP' => 2820,
        'CRC' => 3534,
        'HRK' => 2815,
        'CUP' => 3535,
        'CZK' => 2788,
        'DKK' => 2789,
        'DOP' => 3536,
        'EGP' => 3538,
        'EUR' => 2790,
        'GEL' => 3539,
        'GHS' => 3540,
        'GTQ' => 3541,
        'HNL' => 3542,
        'HKD' => 2792,
        'HUF' => 2793,
        'ISK' => 2818,
        'INR' => 2796,
        'IDR' => 2794,
        'IRR' => 3544,
        'IQD' => 3543,
        'ILS' => 2795,
        'JMD' => 3545,
        'JPY' => 2797,
        'JOD' => 3546,
        'KZT' => 3551,
        'KES' => 3547,
        'KWD' => 3550,
        'KGS' => 3548,
        'LBP' => 3552,
        'MKD' => 3556,
        'MYR' => 2800,
        'MUR' => 2816,
        'MXN' => 2799,
        'MDL' => 3555,
        'MNT' => 3558,
        'MAD' => 3554,
        'MMK' => 3557,
        'NAD' => 3559,
        'NPR' => 3561,
        'TWD' => 2811,
        'NZD' => 2802,
        'NIO' => 3560,
        'NGN' => 2819,
        'NOK' => 2801,
        'OMR' => 3562,
        'PKR' => 2804,
        'PAB' => 3563,
        'PEN' => 2822,
        'PHP' => 2803,
        'PLN' => 2805,
        'GBP' => 2791,
        'QAR' => 3564,
        'RON' => 2817,
        'RUB' => 2806,
        'SAR' => 3566,
        'RSD' => 3565,
        'SGD' => 2808,
        'ZAR' => 2812,
        'KRW' => 2798,
        'SSP' => 3567,
        'VES' => 3573,
        'LKR' => 3553,
        'SEK' => 2807,
        'CHF' => 2785,
        'THB' => 2809,
        'TTD' => 3569,
        'TND' => 3568,
        'TRY' => 2810,
        'UGX' => 3570,
        'UAH' => 2824,
        'AED' => 2813,
        'UYU' => 3571,
        'UZS' => 3572,
        'VND' => 2823,
    ];

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
//        $client = new Client(['base_uri' => 'https://openexchangerates.org']);
//        $codes = CurrencyRepository::DEFAULT_CURRENCIES;
//        $codes = array_merge($codes, CurrencyRepository::MINOR_CURRENCIES);
//
//        $response = $client->get('/api/latest.json?app_id=' . env('OPEN_EXCHANGE_RATES') . '&symbols=' . implode(',', $codes));
//        $rates = json_decode($response->getBody())->rates;
//        $rates = get_object_vars($rates);
//        $currencies = CurrencyRepository::getInstance()->findByCodes($codes);
//
//        foreach ($currencies as $currency) {
//            $rate = $rates[$currency->code];
//            $currency->rate = $rate;
//            $currency->save($currency);
//        }
//
//        echo 'Finished currency update';


        $codes = CurrencyRepository::DEFAULT_CURRENCIES;
        $codes = array_merge($codes, CurrencyRepository::MINOR_CURRENCIES);


        $response = \App\Services\CurrencyExchange\CoinMarketCapExchange::getExchangeRate('USD');


        $response = collect(json_decode($response->body(), true));

        $data = $response->data;
        $value = [
            'symbol' => $data->symbol,
            'coin_market_cap_id' => $data->id,
            'price' => $data->amount,
            'last_updated' => $data->last_updated,
        ];

        ExchangeRate::updateOrCreate([
            'symbol' => $data->symbol,
            'coin_market_cap_id' => $data->id,
        ], $value);


        foreach ($codes as $currency) {
            $value = [
                'symbol' => $currency,
                'coin_market_cap_id' => $this->currencyCodes[$currency],
                'price' => $data->quote->$currency->price,
                'last_updated' => $data->last_updated,
            ];

            ExchangeRate::updateOrCreate([
                'symbol' => $currency,
                'coin_market_cap_id' => $data->id], $value);
        }

        echo 'Finished currency update';
    }
}
