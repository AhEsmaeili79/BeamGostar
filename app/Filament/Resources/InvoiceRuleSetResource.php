<?php

namespace App\Filament\Resources;

use App\Filament\Resources\InvoiceRuleSetResource\Pages;
use App\Models\InvoiceRuleSet;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Tables;
use Morilog\Jalali\Jalalian;

class InvoiceRuleSetResource extends Resource
{
    protected static ?string $model = InvoiceRuleSet::class;

    protected static ?string $navigationLabel = null;  // To be set by a method

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static ?string $navigationGroup = null;

    protected static ?int $navigationSort = 14;
    public static function getLabel(): string
    {
        return __('filament.labels.invoice_set_management');
    }

    public static function getnavigationGroup(): string
    {
        return __('filament.labels.base_info');
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
            ])
           ->extraAttributes([
            'class' => 'filament-form-wrapper', // Adding a wrapper class for custom styles
            'style' => 'border: 3px solid #ddd; 
                        padding: 20px; 
                        border-radius: 12px; 
                        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1); 
                        transition: all 0.3s ease;',
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
                    ->label(__('filament.labels.created_at'))
                    ->formatStateUsing(fn ($state) => 
                        app()->getLocale() === 'fa' 
                            ? Jalalian::fromDateTime($state)->format('Y/m/d H:i') // Convert to Jalali
                            : \Carbon\Carbon::parse($state)->format('Y-m-d H:i') // Gregorian format
                    )
                    ->sortable(),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label(__('filament.labels.updated_at')),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListInvoiceRuleSets::route('/'),
            'create' => Pages\CreateInvoiceRuleSet::route('/create'),
            'edit' => Pages\EditInvoiceRuleSet::route('/{record}/edit'),
        ];
    }
}
