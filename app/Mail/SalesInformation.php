<?php

namespace App\Mail;

use App\Services\OrderService;
use App\Services\ReportService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SalesInformation extends Mailable
{
    use Queueable, SerializesModels;

    public $reportService;
    public $orderService;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(ReportService $reportService, OrderService $orderService)
    {
        $this->reportService = $reportService;
        $this->orderService = $orderService;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $data = $this->reportService->salesPeriod(request());
        $revenue = $data['revenue'];
        $profit = $data['profit'];

        return $this->from(env('MAIL_FROM_ADDRESS', 'teste-fefaf7@inbox.mailtrap.io'))
            ->view('emails.sales.information')
            ->with([
                'sales' => $this->orderService->totalSales(),
                'revenue' => $revenue,
                'profit' => $profit,
                'top10_product_most_sale' => $this->reportService->top10ProductSales(request(),1),
                'top10_product_less_sale' => $this->reportService->top10ProductSales(request(),2),
            ]);
    }
}
