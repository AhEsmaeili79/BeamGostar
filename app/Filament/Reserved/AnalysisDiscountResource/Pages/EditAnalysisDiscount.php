<?php

namespace App\Filament\Resources\AnalysisDiscountResource\Pages;

use App\Filament\Resources\AnalysisDiscountResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditAnalysisDiscount extends EditRecord
{
    protected static string $resource = AnalysisDiscountResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
