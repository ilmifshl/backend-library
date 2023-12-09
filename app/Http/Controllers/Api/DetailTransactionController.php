<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DetailTransaction;
use Illuminate\Support\Facades\Auth;

class DetailTransactionController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if ($user->role === 2) {
            $detailTransactions = DetailTransaction::with(['book', 'transaction']);
        } elseif ($user->role === 1) {
            $detailTransactions = DetailTransaction::with("book")->where('user_id', $user->id)->get();
        } else {
            return response()->json(['error' => 'Unauthorized access'], 403);
        }

        return response()->json($detailTransactions, 200);
    }

    public function show($id)
    {
        $detailTransaction = DetailTransaction::findOrFail($id);
        return response()->json($detailTransaction, 200);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'transaction_id' => 'required',
            'book_id' => 'required',
            'quantity' => 'required'
        ]);

        DetailTransaction::create($validatedData);

        return response()->json(['message' => 'Detail transaksi berhasil ditambah'], 200);
    }

    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'transaction_id' => 'required',
            'book_id' => 'required',
            'quantity' => 'required',
        ]);

        DetailTransaction::whereId($id)->update($validatedData);

        return response()->json(['message' => 'Detail transaksi berhasil diperbarui'], 200);
    }
}
