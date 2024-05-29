<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeleteAccountRequest extends Model
{
    use HasFactory;
    protected $fillable = ['email', 'type'];
}
