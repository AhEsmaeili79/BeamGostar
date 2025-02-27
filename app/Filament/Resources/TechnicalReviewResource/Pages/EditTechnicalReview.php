<?php

namespace App\Filament\Resources\TechnicalReviewResource\Pages;

use App\Filament\Resources\TechnicalReviewResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTechnicalReview extends EditRecord
{
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
    protected static string $resource = TechnicalReviewResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
