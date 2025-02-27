<?php

namespace App\Filament\Resources\InvoiceRuleSetResource\Pages;

use App\Filament\Resources\InvoiceRuleSetResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditInvoiceRuleSet extends EditRecord
{
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
    protected static string $resource = InvoiceRuleSetResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
