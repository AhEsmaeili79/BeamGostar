<?php

namespace App\Filament\Resources\FinancialCheckResource\Pages;

use App\Filament\Resources\FinancialCheckResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditFinancialCheck extends EditRecord
{
    protected static string $resource = FinancialCheckResource::class;

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
