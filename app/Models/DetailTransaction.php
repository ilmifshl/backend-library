<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailTransaction extends Model
{
    use HasFactory;
    protected $table = 'transaction_detail';
    protected $fillable = [
        'transaction_id',
        'book_id',
        'quantity'
    ];

    public function transaction()
    {
        return $this->belongsTo(Transaction::class, 'transaction_id', 'id');
    }
    public function book()
    {
        return $this->belongsTo(Book::class, 'book_id', 'id');
    }
}
