<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TenantDocument extends Model
{
    protected $fillable=[
        'document',
        'bank_statement',
        'previous_lease_contract',
        'memorandum_of_association',
        'trading_license',
        'application_flow_document',
        'property_id',
        'tenant_id',
        'parent_id',
    ];
    use HasFactory;
}
