<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Placemark extends Model
{
protected $fillable = [
    'name',
    'national_id',
    'area_m2',
    'address',
    'description',
    'supplier',
    'geometry',
    'notes',
    'inspector',
    'image1',
    'image2',
    'image3',
    'image4',
    'image5',
    'image6',
    'image7',
    'image8',
];

}
