<?php

namespace App\Filament\Resources\TechnicalReviewResource\Pages;

use App\Filament\Resources\TechnicalReviewResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTechnicalReviews extends ListRecords
{
    protected static string $resource = TechnicalReviewResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
