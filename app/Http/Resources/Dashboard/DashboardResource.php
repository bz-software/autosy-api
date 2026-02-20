<?php

namespace App\Http\Resources\Dashboard;

use App\Http\Resources\Abstracts\NameValueResource;
use App\Http\Resources\Appointment\AppointmentWithDetailsResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DashboardResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "monthlyRevenue" => $this->monthlyRevenue ?? null,
            "openServices" => $this->openServices ?? null,
            "totalClients" => $this->totalClients ?? null,
            "completedServicesThisMonth" => $this->completedServicesThisMonth ?? null,
            "averageTicket" => $this->averageTicket ?? null,
            "averageTicketChange" => $this->averageTicketChange ?? null,
            "monthlyRevenueHistory" => MonthlyRevenueHistory::collection($this->monthlyRevenueHistory ?? []),
            "todayAppointments" => AppointmentDashboardResource::collection($this->todayAppointments ?? []),
            "paymentMethodBreakdown" => NameValueResource::collection($this->paymentMethodBreakdown ?? [])
        ];
    }
}
