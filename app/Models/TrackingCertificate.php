<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TrackingCertificate extends Model
{
// app/Models/TrackingCertificate.php
protected $fillable = [
    'transaction_id',
    'client_name',
    'national_id',
    'transaction_number',
    'building_description',
    'center_name',
    'area',
    'tracking_date',
    'notes',
    'inspector_name',
    'gis_name',
    'tracking_status',  // هنا تضيفه ليتم الحفظ
];

protected $casts = [
    'tracking_status' => 'array',
];


public function transaction()
{
    return $this->belongsTo(Transaction::class);
}

}
