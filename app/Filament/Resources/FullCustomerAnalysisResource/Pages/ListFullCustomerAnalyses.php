<?php

namespace App\Filament\Resources\FullCustomerAnalysisResource\Pages;

use App\Filament\Resources\FullCustomerAnalysisResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListFullCustomerAnalyses extends ListRecords
{
    protected static string $resource = FullCustomerAnalysisResource::class;

    protected function getHeaderActions(): array
    {
        return [
            
        ];
    }
}
