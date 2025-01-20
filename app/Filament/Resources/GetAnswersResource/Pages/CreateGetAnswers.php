<?php

namespace App\Filament\Resources\GetAnswersResource\Pages;

use App\Filament\Resources\GetAnswersResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateGetAnswers extends CreateRecord
{
    protected static string $resource = GetAnswersResource::class;
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
