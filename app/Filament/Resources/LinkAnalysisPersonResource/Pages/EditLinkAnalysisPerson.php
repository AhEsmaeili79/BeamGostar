<?php

namespace App\Filament\Resources\LinkAnalysisPersonResource\Pages;

use App\Filament\Resources\LinkAnalysisPersonResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditLinkAnalysisPerson extends EditRecord
{
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
    protected static string $resource = LinkAnalysisPersonResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
