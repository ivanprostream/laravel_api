<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Wallet extends Model
{
    use HasFactory;

    public $timestamps = true;
    protected $table = "wallets";

    protected $fillable = ["name", "amount", "created_by"];
}