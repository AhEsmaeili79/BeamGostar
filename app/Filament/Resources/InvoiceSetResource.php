<?php

namespace App\Filament\Resources;

use App\Filament\Resources\InvoiceSetResource\Pages;
use App\Models\InvoiceSet;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Tables;
use Morilog\Jalali\Jalalian;
class InvoiceSetResource extends Resource
{
    protected static ?string $model = InvoiceSet::class;

    protected static ?string $navigationIcon = 'heroicon-o-document';

    public static function getNavigationGroup(): string
    {
        return __('filament.labels.base_info');
    }

    public static function getPluralLabel(): string
    {
        return __('filament.labels.max_dayinvoice');
    }

    public static function getNavigationLabel(): string
    {
        return __('filament.labels.max_dayinvoice');
    }

    public static function getLabel(): string
    {
        return __('filament.labels.max_dayinvoice');
    }

    public static function getSingularLabel(): string
    {
        return __('filament.labels.max_dayinvoice');
    }

    protected static ?int $navigationSort = 12;

    public static function form(Forms\Form $form): Forms\Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('max_day')
                    ->label(__('filament.labels.max_day'))
                    ->numeric()
                    ->required()
                    ->placeholder(__('filament.placeholders.enter_max_day')),
            ]);
    }

    public static function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')->sortable()->label(__('filament.labels.id')),
                Tables\Columns\TextColumn::make('max_day')->sortable()->label(__('filament.labels.max_day')),
                Tables\Columns\TextColumn::make('created_at')
                    ->label(__('filament.labels.created_at'))
                    ->formatStateUsing(fn ($state) => 
                        app()->getLocale() === 'fa' 
                            ? Jalalian::fromDateTime($state)->format('Y/m/d H:i') // Convert to Jalali
                            : \Carbon\Carbon::parse($state)->format('Y-m-d H:i') // Gregorian format
                    )
                    ->sortable(),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime('Y-m-d H:i:s')
                    ->sortable()
                    ->label(__('filament.labels.updated_at')),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make()->label(__('filament.actions.edit')),
                Tables\Actions\DeleteAction::make()->label(__('filament.actions.delete')),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make()->label(__('filament.actions.delete_bulk')),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
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
