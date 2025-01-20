<?php

namespace App\Filament\Resources\AnalysisDiscountResource\Pages;

use App\Filament\Resources\AnalysisDiscountResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateAnalysisDiscount extends CreateRecord
{
    protected static string $resource = AnalysisDiscountResource::class;
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
