<?php

namespace App\Filament\Resources\FinancialCheckResource\Pages;

use App\Filament\Resources\FinancialCheckResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateFinancialCheck extends CreateRecord
{
    protected static string $resource = FinancialCheckResource::class;
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
