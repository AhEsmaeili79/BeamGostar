<?php

namespace App\Filament\Resources\CustomerAnalysisResource\Pages;

use App\Filament\Resources\CustomerAnalysisResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCustomerAnalyses extends ListRecords
{
    protected static string $resource = CustomerAnalysisResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
