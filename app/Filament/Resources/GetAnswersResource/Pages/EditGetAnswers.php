<?php

namespace App\Filament\Resources\GetAnswersResource\Pages;

use App\Filament\Resources\GetAnswersResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditGetAnswers extends EditRecord
{
    protected static string $resource = GetAnswersResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
