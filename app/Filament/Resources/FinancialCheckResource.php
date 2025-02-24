<?php

namespace App\Filament\Resources;

use App\Filament\Resources\FinancialCheckResource\Pages;
use App\Models\FinancialCheck;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class FinancialCheckResource extends Resource
{
    protected static ?string $model = FinancialCheck::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'امور مالی';

    protected static ?int $navigationSort = 1;

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
                            ->mapWithKeys(function ($item) {
                                return [$item->id => $item->full_name . ' - ' . $item->analyze_title];
                            });
                    })
                    ->required()
                    ->label(__('filament.labels.customer_analysis')),

                Forms\Components\Textarea::make('scan_form')
                    ->label(__('filament.labels.scan_form'))
                    ->nullable(),

                Forms\Components\TextInput::make('state')
                    ->numeric()
                    ->required()
                    ->label(__('filament.labels.state')),

                Forms\Components\TextInput::make('date_success')
                    ->label(__('filament.labels.date_success'))
                    ->jalali()
                    ->nullable(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('ردیف'),

                Tables\Columns\TextColumn::make('customerAnalysis')
                    ->label(__('filament.labels.customer_analysis'))
                    ->getStateUsing(function ($record) {
                        return $record->customerAnalysis->customer->name_fa . ' ' . $record->customerAnalysis->customer->family_fa . ' - ' . $record->customerAnalysis->analyze->title;
                    }),
                                    
                Tables\Columns\TextColumn::make('scan_form')
                    ->label(__('filament.labels.scan_form'))
                    ->limit(50), // Limits the text shown in the column

                Tables\Columns\TextColumn::make('state')
                    ->label(__('filament.labels.state')),

                Tables\Columns\TextColumn::make('date_success')
                    ->label(__('filament.labels.date_success')),
            ])
            ->filters([
                SelectFilter::make('customer_analysis_id')
                    ->options(function () {
                        return \App\Models\CustomerAnalysis::query()
                            ->join('customers', 'customers.id', '=', 'customer_analysis.customers_id')
                            ->join('analyze', 'analyze.id', '=', 'customer_analysis.analyze_id')
                            ->selectRaw('CONCAT(customers.name_fa, " ", customers.family_fa) AS full_name, analyze.title AS analyze_title, customer_analysis.id')
                            ->get()
                            ->mapWithKeys(function ($item) {
                                return [$item->id => $item->full_name . ' - ' . $item->analyze_title];
                            });
                    })
                    ->label(__('filament.labels.customer_analysis')),
            ])
            ->defaultSort('id', 'desc')
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListFinancialChecks::route('/'),
            'create' => Pages\CreateFinancialCheck::route('/create'),
            'edit' => Pages\EditFinancialCheck::route('/{record}/edit'),
        ];
    }
}
