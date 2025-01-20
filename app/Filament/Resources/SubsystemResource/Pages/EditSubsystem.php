<?php

namespace App\Filament\Resources\SubsystemResource\Pages;

use App\Filament\Resources\SubsystemResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSubsystem extends EditRecord
{
    protected static string $resource = SubsystemResource::class;

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
