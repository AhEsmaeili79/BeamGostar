<?php

namespace App\Filament\Resources\SendSmsResource\Pages;

use App\Filament\Resources\SendSmsResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSendSms extends EditRecord
{
    protected static string $resource = SendSmsResource::class;

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
