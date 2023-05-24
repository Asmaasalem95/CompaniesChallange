<?php


namespace App\Http\Controllers\Api;


use App\Contracts\CommunicationServiceContract;
use App\Contracts\CompanyServiceInterface;
use App\Http\Controllers\Controller;
use App\Http\Requests\filterCompaniesRequest;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class CompanyController extends Controller
{
    /**
     * @var CompanyServiceInterface
     */
    private $companyService;

    /**
     * @var CommunicationServiceContract
     */
    private $communicationService;

    /**
     * CustomerController constructor.
     * @param CompanyServiceInterface $companyService
     * @param CommunicationServiceContract $communicationService
     */
    public function __construct(CompanyServiceInterface $companyService, CommunicationServiceContract $communicationService)
    {
        $this->companyService = $companyService;
        $this->communicationService = $communicationService;
    }

    /**
     * @param filterCompaniesRequest $request
     * @return JsonResponse|object
     */
    public function index(filterCompaniesRequest $request)
    {
        $filters = $request->except('email');

        try {
            $companies = $this->companyService->getFilteredCompanies($filters);
            $chartDataOpen = array_column($companies, 'open');
            $chartDataClose = array_column($companies, 'close');
            //send email
            $subject = $this->companyService->getCompanyNameBySymbol($request->get('symbol'));
            $body = ' From ' . date('Y-m-d', strtotime($request->get('start_date'))) . "  " . ' To ' . date('Y-m-d', strtotime($request->get('end_date')));
            $this->communicationService->send($request->get('email'), $subject, $body);

            return response()->json([
                'status' => 'Success',
                'data' =>
                    ['tableData' => $companies,
                        'chartData' => [
                            'open' => $chartDataOpen,
                            'close' => $chartDataClose
                        ]],
            ])->setStatusCode(Response::HTTP_OK);
        } catch (Exception $e) {

            return response()->json([
                'status' => 'Error',
                'response' => $e->getMessage(),
            ])->setStatusCode(Response::HTTP_BAD_REQUEST);
        }

    }
}
