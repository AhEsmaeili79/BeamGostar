<?php

namespace App\Filament\Resources\AnalysisDelayResource\Pages;

use App\Filament\Resources\AnalysisDelayResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateAnalysisDelay extends CreateRecord
{
    protected static string $resource = AnalysisDelayResource::class;
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
