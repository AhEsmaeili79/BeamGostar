<?php

namespace App\Filament\Resources\RejectResource\Pages;

use App\Filament\Resources\RejectResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListRejects extends ListRecords
{
    protected static string $resource = RejectResource::class;

    protected function getHeaderActions(): array
    {
        return [
        ];
    }
}
