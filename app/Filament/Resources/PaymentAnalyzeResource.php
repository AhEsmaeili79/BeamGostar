<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PaymentAnalyzeResource\Pages;
use App\Models\PaymentAnalyze;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class PaymentAnalyzeResource extends Resource
{
    protected static ?string $model = PaymentAnalyze::class;
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationGroup = 'امور مالی';

    public static function getLabel(): string
    {
        return __('filament.labels.payment_management');
    }

    public static function getPluralLabel(): string
    {
        return __('filament.labels.payment_management');
    }

    public static function getModelLabel(): string
    {
        return __('filament.labels.payment_management');
    }

    public static function getNavigationLabel(): string
    {
        return __('filament.labels.payment_management');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('customer_analysis_id')
                    ->options(function () {
                        return \App\Models\CustomerAnalysis::query()
                            ->join('customers', 'customers.id', '=', 'customer_analysis.customers_id')
                            ->join('analyze', 'analyze.id', '=', 'customer_analysis.analyze_id')
                            ->selectRaw('CONCAT(customers.name_fa, " ", customers.family_fa) AS full_name, analyze.title AS analyze_title, customer_analysis.id')
                            ->get()
                            ->mapWithKeys(fn ($item) => [$item->id => $item->full_name . ' - ' . $item->analyze_title]);
                    })
                    ->required()
                    ->label(__('filament.labels.customer_analysis')),

                Forms\Components\TextInput::make('upload_fish')
                    ->label(__('filament.labels.upload_fish'))
                    ->nullable(),

                Forms\Components\TextInput::make('transaction_id')
                    ->label(__('filament.labels.transaction_id'))
                    ->nullable(),

                Forms\Components\TextInput::make('uniq_id')
                    ->label(__('filament.labels.unique_id'))
                    ->nullable(),

                Forms\Components\TextInput::make('datepay')
                    ->label(__('filament.labels.payment_date'))
                    ->required()
                    ->placeholder(__('filament.labels.payment_date_placeholder')),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label(__('filament.labels.id'))
                    ->sortable(),

                Tables\Columns\TextColumn::make('customerAnalysis')
                    ->label(__('filament.labels.customer_analysis'))
                    ->getStateUsing(fn ($record) => $record->customerAnalysis->customer->name_fa . ' ' . $record->customerAnalysis->customer->family_fa . ' - ' . $record->customerAnalysis->analyze->title),

                Tables\Columns\TextColumn::make('upload_fish')
                    ->label(__('filament.labels.upload_fish'))
                    ->limit(20),

                Tables\Columns\TextColumn::make('transaction_id')
                    ->label(__('filament.labels.transaction_id')),

                Tables\Columns\TextColumn::make('uniq_id')
                    ->label(__('filament.labels.unique_id')),

                Tables\Columns\TextColumn::make('datepay')
                    ->label(__('filament.labels.payment_date')),
            ])
            ->filters([])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPaymentAnalyzes::route('/'),
            'create' => Pages\CreatePaymentAnalyze::route('/create'),
        ];
    }
}
