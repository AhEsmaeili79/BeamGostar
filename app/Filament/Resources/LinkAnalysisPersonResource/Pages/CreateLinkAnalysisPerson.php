<?php

namespace App\Filament\Resources\LinkAnalysisPersonResource\Pages;

use App\Filament\Resources\LinkAnalysisPersonResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateLinkAnalysisPerson extends CreateRecord
{
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
    protected static string $resource = LinkAnalysisPersonResource::class;
}
