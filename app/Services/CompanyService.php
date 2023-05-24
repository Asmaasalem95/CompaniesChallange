<?php


namespace App\Services;


use App\Contracts\CompanyServiceInterface;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Http;

class CompanyService implements CompanyServiceInterface
{

    /**
     * @return array
     */
    public function getCompaniesSymbolList(): array
    {
        $companiesData = $this->getDataCompaniesDataFromEP();
        if (!empty($companiesData)) {
            $symbols = array_unique(array_column($companiesData, 'Symbol'));
        } else {
            $symbols = [];
        }
        return $symbols;

    }

    /**
     * @return array|mixed
     */
    public function getDataCompaniesDataFromEP()
    {
        $response = Http::get('https://pkgstore.datahub.io/core/nasdaq-listings/nasdaq-listed_json/data/a5bc7580d6176d60ac0b2142ca8d7df6/nasdaq-listed_json.json');
        $data = $response->json();
        if ($response->status() === Response::HTTP_OK && is_array($data)) {
            return $data;
        } else return [];
    }

    /**
     * @param string $symbol
     * @return string
     */
    public function getCompanyNameBySymbol(string $symbol): string
    {
        $companiesData = $this->getDataCompaniesDataFromEP();
        $key = array_search($symbol, array_column($companiesData, "Symbol"));

        return $companiesData[$key]['Company Name'];

    }

    /**
     * @param array $filters
     * @return array
     */

    public function getFilteredCompanies(array $filters)
    {
        $data = $this->getFinanceDataFromEP($filters['symbol']);
        if (isset($data['prices'])) {
            $filteredDate = Collect($data['prices'])->whereBetween('date', [strtotime($filters['start_date']), strtotime($filters['end_date'])]);

            return $filteredDate->all();
        } else return [];
    }

    /**
     * @param string $symbol
     * @return mixed
     */
    public function getFinanceDataFromEP(string $symbol)
    {
        $response = Http::withHeaders([
            'X-RapidAPI-Key' => env('RapidAPI_Key'),
            'X-RapidAPI-Host' => 'yh-finance.p.rapidapi.com'
        ])->get('https://yh-finance.p.rapidapi.com/stock/v3/get-historical-data', [
            'symbol' => $symbol
        ]);

        return $response->json();
    }

}
