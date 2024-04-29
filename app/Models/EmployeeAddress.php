<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class EmployeeAddress extends Model
{
    use HasFactory;

    protected $fillable = [
        'nip',
        'identity_number',
        'address',
        'province_id',
        'city_id',
        'district_id',
        'subdistrict_id',
        'rt',
        'rw',
        'is_according_ktp',
    ];

    public function employee()
    {
        return $this->hasOne(Employee::class, 'nip', 'nip');
    }

    /**
     * Get the province of the address.
     */
    public function province()
    {
        return $this->hasOne(Province::class, "id", "province_id");
    }

    /**
     * Get the city of the address.
     */
    public function city()
    {
        return $this->hasOne(City::class, "id", "city_id");
    }

    /**
     * Get the district of the address.
     */
    public function district()
    {
        return $this->hasOne(District::class, "id", "district_id");
    }

    /**
     * Get the subdistrict of the address.
     */
    public function subdistrict()
    {
        return $this->hasOne(Subdistrict::class, "id", "subdistrict_id");
    }
}
