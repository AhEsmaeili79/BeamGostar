<?php

namespace App\Filament\Resources\InvoiceSetResource\Pages;

use App\Filament\Resources\InvoiceSetResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListInvoiceSets extends ListRecords
{
    protected static string $resource = InvoiceSetResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
