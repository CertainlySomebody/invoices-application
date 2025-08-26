<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvoiceProductLine extends Model
{
    use HasFactory, HasUuids;

    public $incrementing = false;

    protected $table = 'invoice_product_lines';
    protected $fillable = ['invoice_id', 'name', 'price', 'quantity'];

    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }
}
