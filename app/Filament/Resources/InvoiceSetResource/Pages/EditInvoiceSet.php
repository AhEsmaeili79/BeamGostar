<?php

namespace App\Filament\Resources\InvoiceSetResource\Pages;

use App\Filament\Resources\InvoiceSetResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditInvoiceSet extends EditRecord
{
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
    protected static string $resource = InvoiceSetResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
