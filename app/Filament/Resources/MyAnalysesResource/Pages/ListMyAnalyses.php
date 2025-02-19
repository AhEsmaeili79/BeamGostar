<?php

namespace App\Filament\Resources\MyAnalysesResource\Pages;

use App\Filament\Resources\MyAnalysesResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListMyAnalyses extends ListRecords
{
    protected static string $resource = MyAnalysesResource::class;

    protected function getHeaderActions(): array
    {
        return [
            
        ];
    }
}
