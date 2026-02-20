<?php

namespace App\Http\Controllers;

use App\Models\Package;
use App\Models\SupportTicket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // Stats cards
        $totalPackages = Package::count();
        $packagesInTransit = Package::where('status', 'in_transit')->count();
        $packagesDelivered = Package::where('status', 'delivered')->count();
        $activeTickets = SupportTicket::where('status', 'open')->count();

        // Recent packages list (last 5)
        $recent_packages = Package::latest()->take(5)->get();

        // Monthly volume chart — real data (last 12 months)
        $monthlyData = Package::select(
            DB::raw("strftime('%Y-%m', created_at) as month"),
            DB::raw('count(*) as total')
        )
        ->where('created_at', '>=', Carbon::now()->subMonths(12))
        ->groupBy('month')
        ->orderBy('month')
        ->pluck('total', 'month');

        $monthlyLabels = [];
        $monthlyCounts = [];
        for ($i = 11; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $key = $date->format('Y-m');
            $monthlyLabels[] = $date->translatedFormat('M');
            $monthlyCounts[] = $monthlyData->get($key, 0);
        }

        // Type breakdown chart — real data
        $typeData = Package::select('type', DB::raw('count(*) as total'))
            ->groupBy('type')
            ->pluck('total', 'type');
        $typeLabels = $typeData->keys()->toArray();
        $typeCounts = $typeData->values()->toArray();

        return view('dashboard', compact(
            'totalPackages',
            'packagesInTransit',
            'packagesDelivered',
            'activeTickets',
            'recent_packages',
            'monthlyLabels',
            'monthlyCounts',
            'typeLabels',
            'typeCounts'
        ));
    }
}
