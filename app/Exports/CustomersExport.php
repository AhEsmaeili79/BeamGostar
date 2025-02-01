<?php

namespace App\Exports;

use App\Models\Customers; // Ensure the model is correctly referenced
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Illuminate\Database\Eloquent\Collection;

class CustomersExport implements FromQuery
{
    use Exportable;

    public $customers;

    public function __construct(Collection $customers) // Accept Eloquent\Collection
    {
        $this->customers = $customers;
    }

    public function query()
    {
        return Customers::query()->whereIn('id', $this->customers->pluck('id')->toArray());
    }
}
