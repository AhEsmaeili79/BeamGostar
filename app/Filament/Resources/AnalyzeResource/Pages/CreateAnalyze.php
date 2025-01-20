<?php

namespace App\Filament\Resources\AnalyzeResource\Pages;

use App\Filament\Resources\AnalyzeResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateAnalyze extends CreateRecord
{
    protected static string $resource = AnalyzeResource::class;
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
