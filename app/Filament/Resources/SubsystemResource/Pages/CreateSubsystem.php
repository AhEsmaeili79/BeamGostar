<?php

namespace App\Filament\Resources\SubsystemResource\Pages;

use App\Filament\Resources\SubsystemResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateSubsystem extends CreateRecord
{
    protected static string $resource = SubsystemResource::class;
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
