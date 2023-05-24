<?php


namespace App\Http\Controllers\Web;


use App\Contracts\CompanyServiceInterface;
use App\Http\Controllers\Controller;

class CompanyController extends Controller
{

    /**
     * @var CompanyServiceInterface
     */
    private $companyService;

    /**
     * CustomerController constructor.
     * @param CompanyServiceInterface $companyService
     */
    public function __construct(CompanyServiceInterface $companyService)
    {
        $this->companyService = $companyService;
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function index()
    {
        $symbolsList = $this->companyService->getCompaniesSymbolList();

        return view('welcome', compact('symbolsList'));
    }

}
