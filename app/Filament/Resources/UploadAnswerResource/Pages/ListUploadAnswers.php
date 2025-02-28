<?php

namespace App\Filament\Resources\UploadAnswerResource\Pages;

use App\Filament\Resources\UploadAnswerResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListUploadAnswers extends ListRecords
{
    protected static string $resource = UploadAnswerResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
