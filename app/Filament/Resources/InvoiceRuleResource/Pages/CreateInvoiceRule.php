<?php

namespace App\Filament\Resources\InvoiceRuleResource\Pages;

use App\Filament\Resources\InvoiceRuleResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateInvoiceRule extends CreateRecord
{
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
    protected static string $resource = InvoiceRuleResource::class;
}
