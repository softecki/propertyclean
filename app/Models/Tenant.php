<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tenant extends Model
{
    use HasFactory;

    protected $fillable=[
        'user_id',
        'title',
        'business_name',
        'business_number',
        'tax_payer_identification',
        'contact_information',
        'family_member',
        'profile',
        'address',
        'country',
        'state',
        'city',
        'zip_code',
        'property',
        'unit',
        'lease_start_date',
        'lease_end_date',
        'application_status',
        'verification_status',
        'approval_status',
        'verified_by',
        'approved_by',
        'verified_at',
        'approved_at',
        'application_notes',
        'is_active',
    ];

    public function properties(){
        return $this->hasOne('App\Models\Property','id','property');
    }
    public function units(){
        return $this->hasOne('App\Models\PropertyUnit','id','unit');
    }

    public function user(){
        return $this->hasOne('App\Models\User','id','user_id');
    }

    public function documents()
    {
        return $this->hasMany('App\Models\TenantDocument','tenant_id','id');
    }
}
