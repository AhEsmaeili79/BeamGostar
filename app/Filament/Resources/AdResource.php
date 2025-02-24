<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AdResource\Pages;
use App\Models\Ad;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class AdResource extends Resource
{
    protected static ?string $model = Ad::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    // Use translation function
    public static function getNavigationLabel(): string
    {
        return __('filament.labels.ad_management');
    }

    public static function getNavigationGroup(): string
    {
        return __('filament.labels.base_info');
    }
    public static function getPluralLabel(): string
    {
        return __('filament.labels.ad_management');
    }

    public static function getLabel(): string
    {
        return __('filament.labels.ad_management');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('title')
                    ->required()
                    ->maxLength(255)
                    ->label(__('filament.labels.title')),

                Forms\Components\Textarea::make('description')
                    ->required()
                    ->label(__('filament.labels.description')),

                Forms\Components\FileUpload::make('image')
                    ->image()
                    ->maxSize(2048) // 2 MB
                    ->nullable()
                    ->label(__('filament.labels.image')),

                Forms\Components\TextInput::make('url')
                    ->url()
                    ->nullable()
                    ->label(__('filament.labels.url')),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->sortable()
                    ->searchable()
                    ->label(__('filament.labels.title')),

                Tables\Columns\TextColumn::make('description')
                    ->limit(50)
                    ->label(__('filament.labels.description')),

                Tables\Columns\ImageColumn::make('image')
                    ->size(50)
                    ->label(__('filament.labels.image')),

                Tables\Columns\TextColumn::make('created_at')
                    ->sortable()
                    ->dateTime()
                    ->label(__('filament.labels.created_at')),
            ])
            ->filters([
                // Add any filters if necessary
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
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
            // Add any relations if necessary
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAds::route('/'),
            'create' => Pages\CreateAd::route('/create'),
            'edit' => Pages\EditAd::route('/{record}/edit'),
        ];
    }
}
