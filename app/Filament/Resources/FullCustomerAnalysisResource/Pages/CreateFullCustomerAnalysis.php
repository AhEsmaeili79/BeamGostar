<?php

namespace App\Filament\Resources\FullCustomerAnalysisResource\Pages;

use App\Filament\Resources\FullCustomerAnalysisResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateFullCustomerAnalysis extends CreateRecord

{

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
    protected static string $resource = FullCustomerAnalysisResource::class;
}
