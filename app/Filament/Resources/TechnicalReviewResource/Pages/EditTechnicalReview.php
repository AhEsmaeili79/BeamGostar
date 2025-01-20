<?php

namespace App\Filament\Resources\TechnicalReviewResource\Pages;

use App\Filament\Resources\TechnicalReviewResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTechnicalReview extends EditRecord
{
    protected static string $resource = TechnicalReviewResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
