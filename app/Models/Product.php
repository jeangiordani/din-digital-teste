<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasFactory;
    
    protected $table = 'products';

    protected $fillable = [
        'user_id',
        'name',
        'price',
        'weight',
    ];

    public function users()
    {
        return $this->belongsTo(User::class);
    }
}
