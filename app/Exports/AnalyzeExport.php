<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use App\Models\Analyze; 
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Illuminate\Database\Eloquent\Collection;

class AnalyzeExport implements FromQuery
{
    /**
    * @return \Illuminate\Support\Collection
    */
    use Exportable;

    public $analyzes;

    public function __construct(Collection $analyzes) // Accept Eloquent\Collection
    {
        $this->analyzes = $analyzes;
    }

    public function query()
    {
        return Analyze::query()->whereIn('id', $this->analyzes->pluck('id')->toArray());
    }
}
