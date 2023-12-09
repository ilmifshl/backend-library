<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $table = 'transactions';
    protected $fillable = [
        'member_id',
        'librarian_id',
        'return_date'
    ];

    public function transactionDetail()
    {
        return $this->hasMany(DetailTransaction::class, 'transaction_id', 'id');
    }

    public function member()
    {
        return $this->belongsTo(User::class, 'member_id');
    }

    public function librarian()
    {
        return $this->belongsTo(User::class, 'librarian_id');
    }

    public function setFineAttribute($value)
    {
        $this->attributes['fine'] = empty($value) ? null : $value;
        
    }
    public function setReturnAttribute($value)
    {
        $this->attributes['return_date'] = empty($value) ? null : $value;
        
    }
}
