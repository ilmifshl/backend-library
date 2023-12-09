<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    use HasFactory;

    protected $table = 'books';
    protected  $fillable = [
        'title',
        'author',
        'category_id',
        'quantity'
    ];

    public function category()
    {
        $this->belongsTo(Category::class);
    }

    public function transactionDetail()
    {
        return $this->hasMany(TransactionDetail::class, 'book_id', 'id');
    }
}
