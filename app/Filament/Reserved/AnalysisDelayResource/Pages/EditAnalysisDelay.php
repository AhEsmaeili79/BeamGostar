<?php

namespace App\Filament\Resources\AnalysisDelayResource\Pages;

use App\Filament\Resources\AnalysisDelayResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditAnalysisDelay extends EditRecord
{
    protected static string $resource = AnalysisDelayResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
