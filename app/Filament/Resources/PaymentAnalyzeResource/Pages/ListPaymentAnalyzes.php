<?php

namespace App\Filament\Resources\PaymentAnalyzeResource\Pages;

use App\Filament\Resources\PaymentAnalyzeResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPaymentAnalyzes extends ListRecords
{
    protected static string $resource = PaymentAnalyzeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
