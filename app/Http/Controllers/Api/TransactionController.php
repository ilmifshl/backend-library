<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\DetailTransaction;
use App\Traits\HttpResponses;

class TransactionController extends Controller
{
    use HttpResponses;

    public function index()
    {
        $user = Auth::user();
        if ($user->role === 2) {
            $transactions = Transaction::with(['member','transactionDetail.book'])->get();
        } elseif ($user->role === 1) {
            $transactions = Transaction::with('transactionDetail')->where('member_id', $user->id)->get();
        } else {
            return $this->error(null, 'Unauthorized access', 403);
        }
        return $this->success($transactions, 'Data retrieved successfully');
    }
    public function store(Request $request)
{
    $validatedData = $request->validate([
        'member_id' => 'required',
        'librarian_id' => 'required',
        'transaction_details' => 'required|array',
        'transaction_details.*.book_id' => 'required|distinct',
        'transaction_details.*.quantity' => 'required|integer|min:1',// Set to nullable if not always present
    ]);

    $transaction = Transaction::with('transactionDetail')->create($validatedData);

    // Menyimpan informasi buku yang dipinjam dalam DetailTransaction
    foreach ($validatedData['transaction_details'] as $detail) {
        $transaction->transactionDetail()->create([
            'book_id' => $detail['book_id'],
            'quantity' => $detail['quantity'],
        ]);
    }

    // Mengurangi stok buku untuk setiap DetailTransaction yang terlibat
    foreach ($transaction->transactionDetail as $detail) {
        $book = $detail->book;
        if ($book) {
            $book->quantity -= $detail->quantity;
            $book->save();
        }
    }

    return $transaction
        ? response()->json(['status' => 200, 'message' => 'Transaction added successfully'], 200)
        : response()->json(['status' => 500, 'message' => 'Something went wrong'], 500);
}



    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'member_id' => 'required',
            'librarian_id' => 'required',
            'fine' => 'required',
            'return_date' => 'required',
        ]);

        $transaction = Transaction::with('transactionDetail')->find($id);
        $transaction->update($validatedData);

        // Ambil detail transaksi yang terkait
        $detailTransactions = $transaction->transactionDetail;

        // Update stok buku yang tersedia jika buku dikembalikan
        foreach ($detailTransactions as $detail) {
            if ($detail->return_date) {
                $book = $detail->book;
                $book->quantity += $detail->quantity;
                $book->save();
            }
        }

        return response()->json($transaction, 200);
    }





    public function show($id)
    {
        $transaction = Transaction::find($id);
        return $transaction
            ? response()->json($transaction, 200)
            : response()->json(['message' => 'Transaction not found'], 404);
    }
}
