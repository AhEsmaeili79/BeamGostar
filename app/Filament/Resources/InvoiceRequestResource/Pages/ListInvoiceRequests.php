<?php

namespace App\Filament\Resources\InvoiceRequestResource\Pages;

use App\Filament\Resources\InvoiceRequestResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListInvoiceRequests extends ListRecords
{
    protected static string $resource = InvoiceRequestResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
