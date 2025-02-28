<?php

namespace App\Filament\Resources\SmstextResource\Pages;

use App\Filament\Resources\SmstextResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSmstext extends EditRecord
{
    protected static string $resource = SmstextResource::class;
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
