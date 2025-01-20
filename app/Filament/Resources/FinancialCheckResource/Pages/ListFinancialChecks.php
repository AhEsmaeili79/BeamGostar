<?php

namespace App\Filament\Resources\FinancialCheckResource\Pages;

use App\Filament\Resources\FinancialCheckResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListFinancialChecks extends ListRecords
{
    protected static string $resource = FinancialCheckResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
