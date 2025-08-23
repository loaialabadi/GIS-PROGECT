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
    'coordinates',
    'purpose', // إضافة الغرض من الشهادة
    'area',
    'tracking_date',
    'notes',
    'inspector_name',
    'gis_name',
    'certificate_path', // مسار الشهادة
    'tracking_status',  // هنا تضيفه ليتم الحفظ
];

protected $casts = [
    'tracking_status' => 'array',
            'delivery_status' => 'array',

];
    public function getTrackingStatusAttribute($value)
    {
        if (is_array($value)) {
            return $value;
        }

        if (is_string($value)) {
            $decoded = json_decode($value, true);
            if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                return $decoded;
            }

            return explode(',', $value);
        }

        return [];
    }

public function transaction()
{
    return $this->belongsTo(Transaction::class);
}



}
