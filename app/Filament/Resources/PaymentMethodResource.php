<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PaymentMethodResource\Pages;
use App\Models\payment_method;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Grid;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Morilog\Jalali\Jalalian;

class PaymentMethodResource extends Resource
{
    protected static ?string $model = payment_method::class;
    public static function getNavigationGroup(): string
    {
        return __('filament.labels.base_info');
    }
    protected static ?string $navigationIcon = 'heroicon-o-currency-dollar';



    protected static ?int $navigationSort = 7;

    public static function getLabel(): string
    {
        return __('filament.labels.payment_method');
    }

    public static function getPluralLabel(): string
    {
        return __('filament.labels.payment_methods');
    }

    public static function getNavigationLabel(): string
    {
        return __('filament.labels.payment_method');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Grid::make(2)
                    ->schema([
                        Toggle::make('status')
                            ->label(__('filament.labels.status'))
                            ->required()
                            ->default(false)
                            ->reactive()
                            ->afterStateUpdated(fn($state) => $state ? 1 : 0)
                            ->offIcon('')
                            ->helperText(__('filament.labels.choose_payment_status'))
                            ->columnSpan(1),

                        TextInput::make('title')
                            ->label(__('filament.labels.title'))
                            ->maxLength(250)
                            ->required()
                            ->placeholder(__('filament.labels.enter_title'))
                            ->columnSpan(2),
                    ]),
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
                Tables\Columns\TextColumn::make('id')
                    ->label(__('filament.labels.id'))
                    ->sortable(),

                Tables\Columns\TextColumn::make('title')
                    ->label(__('filament.labels.title'))
                    ->searchable()
                    ->wrap()
                    ->toggleable()
                    ->sortable(),

                Tables\Columns\IconColumn::make('status')
                    ->label(__('filament.labels.status'))
                    ->icon(fn($state) => $state ? 'heroicon-o-check-circle' : 'heroicon-o-x-circle')
                    ->color(fn($state) => $state ? 'success' : 'danger')
                    ->sortable()
                    ->wrap()
                    ->toggleable()
                    ->searchable(),

                    Tables\Columns\TextColumn::make('created_at')
                    ->label(__('filament.labels.created_at'))
                    ->formatStateUsing(fn ($state) => 
                        app()->getLocale() === 'fa' 
                            ? Jalalian::fromDateTime($state)->format('Y/m/d H:i') // Convert to Jalali
                            : \Carbon\Carbon::parse($state)->format('Y-m-d H:i') // Gregorian format
                    )
                    ->sortable(),
            ])
            ->filters([])
            ->actions([
                Tables\Actions\ViewAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPaymentMethods::route('/'),
            'create' => Pages\CreatePaymentMethod::route('/create'),
        ];
    }
}
