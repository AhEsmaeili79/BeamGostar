<?php

namespace App\Filament\Resources;

use App\Filament\Resources\InvoiceRuleResource\Pages;
use App\Models\InvoiceRule;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Tables;

class InvoiceRuleResource extends Resource
{
    protected static ?string $model = InvoiceRule::class;
    
    // Remove dynamic translation from property
    protected static ?string $navigationLabel = null;  // To be set by a method
    protected static ?string $navigationGroup = null;
    public static function getnavigationGroup(): string
    {
        return __('filament.labels.customers');
    }
    public static function getNavigationLabel(): string
    {
        return __('filament.labels.invoice_rules');
    }

    public static function getLabel(): string
    {
        return __('filament.labels.invoice_rules');
    }

    public static function getSingularLabel(): string
    {
        return __('filament.labels.invoice_rules');
    }

    public static function getPluralLabel(): string
    {
        return __('filament.labels.invoice_rules');
    }

    public static function getNavigationIcon(): string
    {
        return 'heroicon-o-rectangle-stack';  // You can customize this icon if needed
    }

    public static function form(Forms\Form $form): Forms\Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('title')
                    ->label(__('filament.labels.title'))
                    ->required()
                    ->maxLength(200),
                Forms\Components\Textarea::make('text')
                    ->label(__('filament.labels.text'))
                    ->required(),
                Forms\Components\Toggle::make('state')
                    ->label(__('filament.labels.status')),
            ]);
    }

    public static function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')->label(__('filament.labels.title')),
                Tables\Columns\TextColumn::make('text')->label(__('filament.labels.text')),
                Tables\Columns\BooleanColumn::make('state')->label(__('filament.labels.status')),
                Tables\Columns\TextColumn::make('created_at')->label(__('filament.labels.created_at')),
                Tables\Columns\TextColumn::make('updated_at')->label(__('filament.labels.updated_at')),
            ])
            ->filters([
                // Add filters if necessary
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListInvoiceRules::route('/'),
            'create' => Pages\CreateInvoiceRule::route('/create'),
            'edit' => Pages\EditInvoiceRule::route('/{record}/edit'),
        ];
    }
}
