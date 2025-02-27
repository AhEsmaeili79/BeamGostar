<?php

namespace App\Filament\Resources\InvoiceRuleSetResource\Pages;

use App\Filament\Resources\InvoiceRuleSetResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateInvoiceRuleSet extends CreateRecord
{
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
    protected static string $resource = InvoiceRuleSetResource::class;
}
