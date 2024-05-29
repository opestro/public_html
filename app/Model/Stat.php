<?php
namespace App\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stat extends Model
{
    use HasFactory;
    protected $fillable = ['orders', 'stores', 'viewers', 'returns', 'customers'];
}
