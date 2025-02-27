<?php

namespace App\Filament\Resources\PaymentAnalyzeResource\Pages;

use App\Filament\Resources\PaymentAnalyzeResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreatePaymentAnalyze extends CreateRecord
{
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
    protected static string $resource = PaymentAnalyzeResource::class;
}
