<?php

namespace App\Filament\Resources\SmstextResource\Pages;

use App\Filament\Resources\SmstextResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSmstexts extends ListRecords
{
    protected static string $resource = SmstextResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
