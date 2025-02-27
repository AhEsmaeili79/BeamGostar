<?php

namespace App\Filament\Resources\TechnicalReviewResource\Pages;

use App\Filament\Resources\TechnicalReviewResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateTechnicalReview extends CreateRecord
{
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
    protected static string $resource = TechnicalReviewResource::class;
}
