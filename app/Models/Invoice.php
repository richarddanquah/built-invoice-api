<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id','amount','status','issue_date','due_date','paid_date','payment_method','payment_ref'
     ];

    public function customer(){
        return $this->belongsTo(Customer::class);
    }

    public function items(){
        return $this->belongsToMany(Items::class, 'invoice_items', 'invoice_id');
    }
}
