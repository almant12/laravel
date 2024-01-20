<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ConfirmationToken extends Model
{
    use HasFactory;

    protected $fillable = [
        'confirmed',
        // Add other fillable attributes as needed
    ];

    public $timestamps = false;


    public function user(){
        return $this->belongsTo(User::class,'user_id');
    }
}
