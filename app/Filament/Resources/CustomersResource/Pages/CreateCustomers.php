<?php

namespace App\Filament\Resources\CustomersResource\Pages;

use App\Filament\Resources\CustomersResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateCustomers extends CreateRecord
{
    protected static string $resource = CustomersResource::class;
    public function getTitle(): string  // Make this method public
    {
        return 'ایجاد مشتری جدید'; // Your custom title for the form page
    }
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
