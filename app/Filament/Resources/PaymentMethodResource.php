<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PaymentMethodResource\Pages;
use App\Models\payment_method;
use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Grid;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class PaymentMethodResource extends Resource
{
    protected static ?string $model = payment_method::class;
    protected static ?string $navigationGroup = 'اطلاعات پایه';
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
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
                    ->wrap()
                    ->toggleable()
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
