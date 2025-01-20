<?php

namespace App\Filament\Resources\GetAnswersResource\Pages;

use App\Filament\Resources\GetAnswersResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListGetAnswers extends ListRecords
{
    protected static string $resource = GetAnswersResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
