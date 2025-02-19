<?php

namespace App\Filament\Resources\InvoiceRuleResource\Pages;

use App\Filament\Resources\InvoiceRuleResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListInvoiceRules extends ListRecords
{
    protected static string $resource = InvoiceRuleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
