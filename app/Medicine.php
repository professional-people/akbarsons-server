<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Medicine extends Model
{
    public function getMedicineType()
    {
        return $this->hasOne(MedicineType::class, 'id', 'type_id');
    }

    public function getMedicineCompany()
    {
        return $this->hasOne(Company::class, 'id', 'company_id');
    }

    public function getVendor()
    {
        return $this->hasOne(Vendor::class, 'id', 'vendor_id');
    }
}
