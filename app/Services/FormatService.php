<?php

namespace BynqIO\Dynq\Services;

use App\Ninja\Datatables\TaxRateDatatable;
use App\Ninja\Repositories\TaxRateRepository;

/**
 * Class FormatService.
 */
class FormatService extends BaseService
{
    /**
     * @var TaxRateRepository
     */
    protected $taxRateRepo;

    /**
     * @var DatatableService
     */
    protected $datatableService;

    /**
     * TaxRateService constructor.
     *
     * @param TaxRateRepository $taxRateRepo
     * @param DatatableService  $datatableService
     */
    public function __construct(TaxRateRepository $taxRateRepo, DatatableService $datatableService)
    {
        $this->taxRateRepo = $taxRateRepo;
        $this->datatableService = $datatableService;
    }

    /**
     * @return TaxRateRepository
     */
    protected function getRepo()
    {
        return $this->taxRateRepo;
    }

    /**
     * @param $accountId
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getDatatable($accountId)
    {
        $datatable = new TaxRateDatatable(false);
        $query = $this->taxRateRepo->find($accountId);

        return $this->datatableService->createDatatable($datatable, $query);
    }

    public static function monetary($amount)
    {
        return number_format($amount, LOCALE_DECIMALS, LOCALE_DECIMAL, LOCALE_SEPARATOR);
    }

    public static function monetaryJS($input)
    {
        return "{$input}, '" . LOCALE_DECIMALS . "', '" . LOCALE_DECIMAL . "', '" . LOCALE_SEPARATOR . "'";
    }

    public static function dateFormatJS()
    {
        return LOCALE_DATE;
    }
}

/*
class MoneyDirective {
    public function boot() {
        Blade::directive('money', function ($expression) {
            $class = self::class;
            return "<?php echo $class::render(\$__env, $expression) ?>";
        });
    }

    public function render($env, $amount, $symbol = true) {
        return "Someone should implement MoneyDirective::render...";
    }
}*/

// Call MoneyDirective::boot() in your service provider's boot()