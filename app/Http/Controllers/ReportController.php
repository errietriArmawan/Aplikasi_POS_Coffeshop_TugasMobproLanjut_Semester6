<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    // Total penjualan per hari
    public function salesDaily()
    {
        $data = DB::table('transaction_products')
            ->join('transactions', 'transactions.id', '=', 'transaction_products.transaction_id')
            ->selectRaw('DATE(transactions.created_at) as tanggal, SUM(transaction_products.total) as total_penjualan')
            ->groupBy(DB::raw('DATE(transactions.created_at)'))
            ->orderByDesc('tanggal')
            ->get();

        return response()->json($data);
    }

    // Total penjualan per bulan
    public function salesMonthly()
    {
        $data = DB::table('transaction_products')
            ->join('transactions', 'transactions.id', '=', 'transaction_products.transaction_id')
            ->selectRaw("DATE_FORMAT(transactions.created_at, '%Y-%m') as bulan, SUM(transaction_products.total) as total_penjualan")
            ->groupBy(DB::raw("DATE_FORMAT(transactions.created_at, '%Y-%m')"))
            ->orderByDesc('bulan')
            ->get();

        return response()->json($data);
    }
}
