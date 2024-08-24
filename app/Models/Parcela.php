<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Parcela extends Model
{
    use HasFactory;

    protected $fillable = [
        'carne_id',
        'data_vencimento',
        'valor',
        'numero',
        'entrada',
    ];

    public function carne()
    {
        return $this->belongsTo(Carne::class);
    }
}
