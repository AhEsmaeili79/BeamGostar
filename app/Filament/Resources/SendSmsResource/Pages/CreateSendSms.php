<?php

namespace App\Filament\Resources\SendSmsResource\Pages;

use App\Filament\Resources\SendSmsResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateSendSms extends CreateRecord
{
    protected static string $resource = SendSmsResource::class;
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
