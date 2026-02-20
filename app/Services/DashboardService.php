<?php
namespace App\Services;

use App\DTOs\CustomerDTO;
use App\Enums\PaymentMethod;
use App\Exceptions\ServiceException;
use App\Repositories\AppointmentRepository;
use App\Repositories\AppointmentServiceRepository;
use App\Repositories\CustomerRepository;
use Carbon\Carbon;
use phpDocumentor\Reflection\Types\Object_;

class DashboardService {
    public function __construct(
        private AppointmentRepository $rAppointment,
        private AppointmentServiceRepository $rAppointmentService,
        private CustomerRepository $rCustomer
    ) {}

    public function index($idWorkshop){
        $carbon = new Carbon();
        $startMonth = $carbon->copy()->startOfMonth()->format('Y-m-d');
        $endMonth = $carbon->copy()->endOfMonth()->format('Y-m-d');
        $today = $carbon->copy()->now()->format('Y-m-d');

        $lastMonth = $carbon->copy()->subMonth();
        $lastStart = $lastMonth->copy()->startOfMonth()->format('Y-m-d');
        $lastEnd = $lastMonth->copy()->endOfMonth()->format('Y-m-d');

        $todayAppointments = $this->rAppointment->byDateDashboard($today, $idWorkshop);

        $monthlyRevenue = $this->rAppointmentService->periodRevenue($idWorkshop, $startMonth, $endMonth);

        $appointmentsInProgress = $this->rAppointment->countInProgress($idWorkshop);
        
        $customers = $this->rCustomer->countByWorkshop($idWorkshop);

        $completedServicesThisMonth = $this->rAppointment->countCompletedByDate($idWorkshop, $startMonth, $endMonth);

        $averageTicket = $completedServicesThisMonth > 0
            ? $monthlyRevenue / $completedServicesThisMonth
            : 0;

        $previousMonthRevenue = $this->rAppointmentService->periodRevenue($idWorkshop, $lastStart, $lastEnd);

        if ($previousMonthRevenue > 0) {
            $variation = (
                ($monthlyRevenue - $previousMonthRevenue)
                / $previousMonthRevenue
            ) * 100;
        } else {
            $variation = $monthlyRevenue > 0 ? 100 : 0;
        }

        $monthlyRevenueHistory = $this->lastSixMonthsRevenue($idWorkshop);

        $byPaymentMethod = $this->byPaymentMethod($idWorkshop, $startMonth, $endMonth);

        return (object) [
            'todayAppointments' => $todayAppointments,
            'monthlyRevenue' => $monthlyRevenue,
            'openServices' => $appointmentsInProgress,
            'totalClients' => $customers,
            'completedServicesThisMonth' => $completedServicesThisMonth,
            'averageTicket' => number_format($averageTicket, 2, '.', ''),
            'averageTicketChange' => number_format($variation, 2, '.', ''),
            'monthlyRevenueHistory' => $monthlyRevenueHistory,
            'paymentMethodBreakdown' => $byPaymentMethod
        ];
    }

    private function byPaymentMethod($idWorkshop, $start, $end){
        $appointments = $this->rAppointment->byPaymentMethodPeriod($idWorkshop, $start, $end);
        $appointments = $appointments->toArray();

        $totalAppointments = collect($appointments)->sum('total');
        $grouped = collect($appointments)->keyBy('payment_method');

        $paymentMethodBreakdown = [];

        foreach (PaymentMethod::cases() as $method) {

            $count = $grouped[$method->value]['total'] ?? 0;

            $paymentMethodBreakdown[] = (object) [
                "name" => $method->label(),
                "value" => $totalAppointments > 0
                    ? round(($count / $totalAppointments) * 100, 2)
                    : 0
            ];
        }

        return $paymentMethodBreakdown;
    }

    private function lastSixMonthsRevenue($idWorkshop)
    {
        $now = Carbon::now();
        $months = [];

        for ($i = 5; $i >= 0; $i--) {

            $date = $now->copy()->subMonths($i);

            $startMonth = $date->copy()->startOfMonth()->format('Y-m-d');
            $endMonth = $date->copy()->endOfMonth()->format('Y-m-d');

            $revenue = $this->rAppointmentService
                ->periodRevenue($idWorkshop, $startMonth, $endMonth) ?? 0;

            $month = $date->translatedFormat('M');

            $months[] = (object) [
                "month" => $month, // Jan, Fev, Mar...
                "value" => floatval($revenue)
            ];
        }

        return $months;
    }
}

