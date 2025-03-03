<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Customer extends Model
{
    //
    use HasFactory, SoftDeletes;

    protected $fillable = ['name', 'reference', 'category_id', 'start_date', 'description'];
    protected $table = 'customers';

    public function contacts() {
        return $this->hasMany(Contact::class);
    }

    public function category() {
        return $this->belongsTo(Category::class);
    }
}
