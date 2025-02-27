<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AdResource\Pages;
use App\Models\Ad;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Morilog\Jalali\Jalalian;

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
                    ->label(__('filament.labels.created_at'))
                    ->formatStateUsing(fn ($state) => 
                        app()->getLocale() === 'fa' 
                            ? Jalalian::fromDateTime($state)->format('Y/m/d H:i') // Convert to Jalali
                            : \Carbon\Carbon::parse($state)->format('Y-m-d H:i') // Gregorian format
                    )
                    ->sortable(),
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
