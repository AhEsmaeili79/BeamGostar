<?php

namespace App\Filament\Resources\InvoiceRuleSetResource\Pages;

use App\Filament\Resources\InvoiceRuleSetResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListInvoiceRuleSets extends ListRecords
{
    protected static string $resource = InvoiceRuleSetResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
