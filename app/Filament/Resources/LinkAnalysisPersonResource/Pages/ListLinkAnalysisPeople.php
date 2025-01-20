<?php

namespace App\Filament\Resources\LinkAnalysisPersonResource\Pages;

use App\Filament\Resources\LinkAnalysisPersonResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListLinkAnalysisPeople extends ListRecords
{
    protected static string $resource = LinkAnalysisPersonResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
