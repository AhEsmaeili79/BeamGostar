<?php

namespace App\Filament\Resources\SendSmsResource\Pages;

use App\Filament\Resources\SendSmsResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSendSms extends ListRecords
{
    protected static string $resource = SendSmsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
