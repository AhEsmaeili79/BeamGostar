<?php

namespace App\Filament\Resources\AnalysisTimeResource\Pages;

use App\Filament\Resources\AnalysisTimeResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListAnalysisTimes extends ListRecords
{
    protected static string $resource = AnalysisTimeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
