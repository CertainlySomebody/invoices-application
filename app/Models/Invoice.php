<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\Invoices\Domain\Enums\StatusEnum;

class Invoice extends Model
{
    use HasFactory, HasUuids;

    public $incrementing = false;
    protected $table = 'invoices';
    protected $fillable = ['customer_name', 'customer_email', 'status'];

    protected $casts = [
        'status' => StatusEnum::class
    ];

    public function productLines()
    {
        return $this->hasMany(InvoiceProductLine::class);
    }
}
