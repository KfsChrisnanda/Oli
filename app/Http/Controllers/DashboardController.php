<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Supplier;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        // --- Ambil parameter filter ---
        $period = $request->get('period', 'today');
        $startDate = null;
        $endDate = null;

        // --- Tentukan rentang tanggal berdasarkan periode ---
        switch ($period) {
            case 'today':
                $startDate = Carbon::today();
                $endDate = Carbon::today()->endOfDay();
                break;
            case 'this_week':
                $startDate = Carbon::now()->startOfWeek();
                $endDate = Carbon::now()->endOfWeek();
                break;
            case 'this_month':
                $startDate = Carbon::now()->startOfMonth();
                $endDate = Carbon::now()->endOfMonth();
                break;
            case 'last_month':
                $startDate = Carbon::now()->subMonth()->startOfMonth();
                $endDate = Carbon::now()->subMonth()->endOfMonth();
                break;
            case 'this_year':
                $startDate = Carbon::now()->startOfYear();
                $endDate = Carbon::now()->endOfYear();
                break;
            case 'custom':
                if ($request->start_date && $request->end_date) {
                    $startDate = Carbon::parse($request->start_date)->startOfDay();
                    $endDate = Carbon::parse($request->end_date)->endOfDay();
                } else {
                    $startDate = Carbon::today();
                    $endDate = Carbon::today()->endOfDay();
                }
                break;
            default:
                $startDate = Carbon::today();
                $endDate = Carbon::today()->endOfDay();
        }

        // --- Statistik berdasarkan periode yang dipilih ---
        $salesToday = Transaction::whereBetween('created_at', [$startDate, $endDate])->sum('total_amount');
        $transactionsTodayCount = Transaction::whereBetween('created_at', [$startDate, $endDate])->count();

        // Keuntungan berdasarkan periode (Harga Jual - Harga Beli)
        $profitToday = TransactionDetail::whereHas('transaction', function ($query) use ($startDate, $endDate) {
            $query->whereBetween('created_at', [$startDate, $endDate]);
        })->select(DB::raw('SUM(transaction_details.quantity * (transaction_details.price - products.buy_price)) as total_profit'))
        ->join('products', 'products.id', '=', 'transaction_details.product_id')
        ->value('total_profit') ?? 0;

        // --- Statistik Keseluruhan ---
        $totalProducts = Product::count();
        $lowStockProductsCount = Product::whereColumn('stock', '<=', 'minimum_stock')->count();
        $lowStockProductsList = Product::with('category')->whereColumn('stock', '<=', 'minimum_stock')->limit(5)->get();
        $totalSuppliers = Supplier::count();

        // --- Produk Terlaris Bulan Ini ---
        $startOfMonth = Carbon::now()->startOfMonth();
        $endOfMonth = Carbon::now()->endOfMonth();
        $topSellingProducts = TransactionDetail::whereBetween('created_at', [$startOfMonth, $endOfMonth])
            ->select('product_id', DB::raw('SUM(quantity) as total_quantity'))
            ->groupBy('product_id')
            ->orderBy('total_quantity', 'desc')
            ->with(['product' => function($query) {
                $query->withTrashed(); // Include soft deleted products
            }])
            ->limit(5)
            ->get();

        // --- Data untuk Grafik Penjualan berdasarkan periode ---
        if ($period == 'today') {
            // Grafik per jam untuk hari ini
            $salesDataChart = Transaction::select(
                    DB::raw('HOUR(created_at) as hour'),
                    DB::raw('SUM(total_amount) as total')
                )
                ->whereBetween('created_at', [$startDate, $endDate])
                ->groupBy('hour')
                ->orderBy('hour', 'asc')
                ->get();

            $chartLabels = [];
            $chartValues = [];
            // Inisialisasi 24 jam dengan penjualan 0
            for ($i = 0; $i < 24; $i++) {
                $hour = sprintf('%02d:00', $i);
                $chartLabels[] = $hour;
                $chartValues[$i] = 0;
            }
            foreach ($salesDataChart as $data) {
                $chartValues[$data->hour] = $data->total;
            }
        } else {
            // Grafik harian untuk periode lainnya
            $salesDataChart = Transaction::select(
                    DB::raw('DATE(created_at) as date'),
                    DB::raw('SUM(total_amount) as total')
                )
                ->whereBetween('created_at', [$startDate, $endDate])
                ->groupBy('date')
                ->orderBy('date', 'asc')
                ->get();

            $chartLabels = [];
            $chartValues = [];
            
            // Generate semua tanggal dalam rentang
            $currentDate = $startDate->copy();
            while ($currentDate <= $endDate) {
                $dateStr = $currentDate->format('Y-m-d');
                $labelStr = $currentDate->format('d M');
                $chartLabels[] = $labelStr;
                $chartValues[$dateStr] = 0;
                $currentDate->addDay();
            }
            
            foreach ($salesDataChart as $data) {
                $chartValues[$data->date] = $data->total;
            }
        }

        // Kirim semua data ke view
        $stats = [
            'salesToday' => $salesToday,
            'profitToday' => $profitToday,
            'transactionsTodayCount' => $transactionsTodayCount,
            'totalProducts' => $totalProducts,
            'lowStockProductsCount' => $lowStockProductsCount,
            'lowStockProductsList' => $lowStockProductsList,
            'totalSuppliers' => $totalSuppliers,
            'topSellingProducts' => $topSellingProducts,
            'salesChartData' => json_encode(['labels' => $chartLabels, 'values' => array_values($chartValues)]),
        ];

        return view('dashboard', compact('stats'));
    }
}