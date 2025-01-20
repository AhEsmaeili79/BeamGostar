<?php

namespace App\Filament\Resources\CustomerAnalysisResource\Pages;

use App\Filament\Resources\CustomerAnalysisResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateCustomerAnalysis extends CreateRecord
{
    protected static string $resource = CustomerAnalysisResource::class;
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
