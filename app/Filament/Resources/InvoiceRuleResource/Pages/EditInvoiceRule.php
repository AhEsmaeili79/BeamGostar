<?php

namespace App\Filament\Resources\InvoiceRuleResource\Pages;

use App\Filament\Resources\InvoiceRuleResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditInvoiceRule extends EditRecord
{
    protected static string $resource = InvoiceRuleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
