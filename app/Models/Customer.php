<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = [
        'fullname','phonenumber','email','address','city','postal_code','user_id'
     ];

     public function invoices(){
        return $this->hasMany(Invoice::class);
    }

    public function user(){
        return $this->belongsTo(User::class);
    }

}
