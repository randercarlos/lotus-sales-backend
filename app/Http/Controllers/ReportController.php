<?php

namespace App\Http\Controllers;

use App\Mail\SalesInformation;
use App\Services\ReportService;
use App\Services\OrderService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ReportController extends Controller
{
    private $reportService;

    public function __construct(ReportService $reportService) {
        $this->reportService = $reportService;
    }

    public function salesPeriod(Request $request) {
        return $this->reportService->salesPeriod($request);
    }

    public function top10ProductSales(Request $request) {
        return $this->reportService->top10ProductSales($request);
    }

    public function shipSalesInfo() {
        Mail::to(env('MAIL_TO_ADDRESS', 'teste-fefaf7@inbox.mailtrap.io'))
            ->send(new SalesInformation(new ReportService(), new OrderService()));
    }
}
