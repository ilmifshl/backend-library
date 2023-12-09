<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\DetailTransaction;
use App\Models\Transaction;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $totalBooks = Book::count();
        $ongoingTransactions = Transaction::where('return_date', null)->count();
        $completedTransactions = Transaction::where('return_date', !null)->count();

        $dashboardData = [
            'totalBooks' => $totalBooks,
            'ongoingTransactions' => $ongoingTransactions,
            'completedTransactions' => $completedTransactions
        ];

        return response()->json(['status' => 200, 'dashboardData' => $dashboardData], 200);
    }
}

