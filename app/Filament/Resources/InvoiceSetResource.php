<?php

namespace App\Filament\Resources;

use App\Filament\Resources\InvoiceSetResource\Pages;
use App\Models\InvoiceSet;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class InvoiceSetResource extends Resource
{
    protected static ?string $model = InvoiceSet::class;

    // Remove dynamic translations from here
    protected static ?string $navigationLabel = null;  // To be set by a method

    protected static ?string $navigationGroup = null;

    public static function getLabel(): string
    {
        return __('filament.labels.invoice_set_management');
    }

    public static function getnavigationGroup(): string
    {
        return __('filament.labels.invoice_management');
    }

    // Move this dynamic property to a method
    public static function getNavigationLabel(): string
    {
        return __('filament.labels.invoice_set_management');
    }


    public static function getSingularLabel(): string
    {
        return __('filament.labels.invoice_set_management');
    }

    public static function getPluralLabel(): string
    {
        return __('filament.labels.invoice_set_management');
    }

    public static function form(Forms\Form $form): Forms\Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('title')
                    ->required()
                    ->maxLength(200)
                    ->label(__('filament.labels.title')),
                Forms\Components\Textarea::make('text')
                    ->required()
                    ->label(__('filament.labels.text')),
                Forms\Components\Toggle::make('state')
                    ->label(__('filament.labels.status')),
            ]);
    }

    public static function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->label(__('filament.labels.title')),
                Tables\Columns\TextColumn::make('text')
                    ->label(__('filament.labels.text')),
                Tables\Columns\BooleanColumn::make('state')
                    ->label(__('filament.labels.status')),
                Tables\Columns\TextColumn::make('created_at')
                    ->label(__('filament.labels.created_at')),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label(__('filament.labels.updated_at')),
            ])
            ->filters([
                // You can add filters here if necessary
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListInvoiceSets::route('/'),
            'create' => Pages\CreateInvoiceSet::route('/create'),
            'edit' => Pages\EditInvoiceSet::route('/{record}/edit'),
        ];
    }
}
