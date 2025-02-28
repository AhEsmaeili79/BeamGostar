<?php

namespace App\Filament\Resources\SmstextResource\Pages;

use App\Filament\Resources\SmstextResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateSmstext extends CreateRecord
{
    protected static string $resource = SmstextResource::class;
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
