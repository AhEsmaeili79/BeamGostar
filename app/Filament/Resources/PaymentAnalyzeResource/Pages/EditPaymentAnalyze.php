<?php

namespace App\Filament\Resources\PaymentAnalyzeResource\Pages;

use App\Filament\Resources\PaymentAnalyzeResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPaymentAnalyze extends EditRecord
{
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
    protected static string $resource = PaymentAnalyzeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
