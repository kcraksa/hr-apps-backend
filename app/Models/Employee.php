<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Employee extends Model
{
    use HasFactory;

    public function PlaceOffice(): HasOne
    {
        return $this->hasOne(PlaceOffice::class, "id", "office_place_id");
    }

    public function Department(): HasOne
    {
        return $this->hasOne(Department::class, "id", "department_id");
    }

    public function Section(): HasOne
    {
        return $this->hasOne(Section::class, "id", "section_id");
    }

    public function Position(): HasOne
    {
        return $this->hasOne(Position::class, "id", "position_id");
    }

    public function Level(): HasOne
    {
        return $this->hasOne(Level::class, "id", "level_id");
    }

    public function Superior(): HasOne
    {
        return $this->hasOne(Employee::class, "nip", "superior_nip");
    }

    public function Address(): HasOne
    {
        return $this->hasOne(EmployeeAddress::class, "nip", "nip");
    }

    public function PersonalData(): HasOne
    {
        return $this->hasOne(EmployeePersonalData::class, "nip", "nip");
    }
}
