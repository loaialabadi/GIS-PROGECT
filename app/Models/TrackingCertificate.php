<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TrackingCertificate extends Model
{
    protected $fillable = [
        'client_name',
        'national_id',
        'transaction_id',
        'building_description',
        'project_name',
        'area',
        'tracking_date',
        'notes',
        'inspector_name',
    ];
}
