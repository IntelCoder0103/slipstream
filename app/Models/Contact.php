<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Contact extends Model
{
    //
    use HasFactory, SoftDeletes;

    protected $fillable = ['first_name', 'last_name', 'customer_id'];

    public function customer() {
        return $this->belongsTo(Customer::class);
    }
}
