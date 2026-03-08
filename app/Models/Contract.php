<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Tenant;

class Contract extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'tenant_id',
        'lease_start_date',
        'lease_end_date',
        'lease_tenure',
        'amount',
        'amount_paid',
        'amount_remained',
        'lease_terms',
        'lease_rate',
        'increments',
        'payment_cycle',
        'penalty',
        'discount',
        'contract_status',
        'status',
        'payment_date',
    ];

    public function tenant()
    {
        return $this->belongsTo(Tenant::class, 'tenant_id');
    }
}
