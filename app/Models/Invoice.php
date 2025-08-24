<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory, HasUuids;

    public $incrementing = false;
    protected $fillable = ['customer_name', 'customer_email', 'status'];

    public function productLines()
    {
        return $this->hasMany(InvoiceProductLine::class);
    }
}
