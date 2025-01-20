<?php

namespace App\Filament\Resources\AnalysisTimeResource\Pages;

use App\Filament\Resources\AnalysisTimeResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateAnalysisTime extends CreateRecord
{
    protected static string $resource = AnalysisTimeResource::class;
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
