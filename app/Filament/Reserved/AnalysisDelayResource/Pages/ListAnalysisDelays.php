<?php

namespace App\Filament\Resources\AnalysisDelayResource\Pages;

use App\Filament\Resources\AnalysisDelayResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListAnalysisDelays extends ListRecords
{
    protected static string $resource = AnalysisDelayResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
