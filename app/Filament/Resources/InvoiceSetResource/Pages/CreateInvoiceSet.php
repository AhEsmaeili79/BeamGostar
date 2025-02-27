<?php

namespace App\Filament\Resources\InvoiceSetResource\Pages;

use App\Filament\Resources\InvoiceSetResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateInvoiceSet extends CreateRecord
{
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
    protected static string $resource = InvoiceSetResource::class;
}
