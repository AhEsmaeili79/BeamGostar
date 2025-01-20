<?php

namespace App\Filament\Resources\AnalysisTimeResource\Pages;

use App\Filament\Resources\AnalysisTimeResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditAnalysisTime extends EditRecord
{
    protected static string $resource = AnalysisTimeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
