<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;

    protected $fillable = [
        'name','image_url','price','description','stock','user_id'
     ];

    public function item_stocks(){
        return $this->hasMany(ItemStock::class);
    }

    public function invoices(){
        return $this->belongsToMany(Invoice::class, 'invoice_items', 'item_id');
    }

    public function user(){
        return $this->belongsTo(User::class);
    }

}
