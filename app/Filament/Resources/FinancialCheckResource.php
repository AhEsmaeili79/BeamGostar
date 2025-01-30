<?php

namespace App\Filament\Resources;

use App\Filament\Resources\FinancialCheckResource\Pages;
use App\Filament\Resources\FinancialCheckResource\RelationManagers;
use App\Models\FinancialCheck;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class FinancialCheckResource extends Resource
{
    protected static ?string $model = FinancialCheck::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationLabel = 'چک مالی';

    protected static ?string $pluralLabel = 'چک مالی';

    protected static ?string $modelLabel = 'چک مالی';

    protected static ?string $navigationGroup = 'امور مالی';


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('customer_analysis_id')
                ->options(function () {
                    return \App\Models\CustomerAnalysis::query()
                        ->join('customers', 'customers.id', '=', 'customer_analysis.customers_id')  // Join customers
                        ->join('analyze', 'analyze.id', '=', 'customer_analysis.analyze_id')  // Join analyze
                        ->selectRaw('CONCAT(customers.name_fa, " ", customers.family_fa) AS full_name, analyze.title AS analyze_title, customer_analysis.id')
                        ->get()
                        ->mapWithKeys(function ($item) {
                            // Combine the customer full name and analyze title
                            return [$item->id => $item->full_name . ' - ' . $item->analyze_title];
                        });
                })
                ->required()
                ->label('آنالیز مشتریان'),

                Forms\Components\Textarea::make('scan_form')
                    ->label('اسکن فرم')
                    ->nullable(),

                Forms\Components\TextInput::make('state')
                    ->numeric()
                    ->required()
                    ->label('وضعیت'),

                Forms\Components\TextInput::make('date_success')
                    ->label('تاریخ تایید')
                    ->nullable(),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('کد'),

                Tables\Columns\TextColumn::make('customerAnalysis')
                    ->label('آنالیز مشتریان')
                    ->getStateUsing(function ($record) {
                        return $record->customerAnalysis->customer->name_fa . ' ' . $record->customerAnalysis->customer->family_fa . ' - ' . $record->customerAnalysis->analyze->title;
                    }),
                                    
                Tables\Columns\TextColumn::make('scan_form')
                    ->label('اسکن فرم')
                    ->limit(50), // Limits the text shown in the column

                Tables\Columns\TextColumn::make('state')
                    ->label('وضعیت'),

                Tables\Columns\TextColumn::make('date_success')
                    ->label('تاریخ تایید'),
            ])
            ->filters([
                SelectFilter::make('customer_analysis_id')
                    ->options(function () {
                        return \App\Models\CustomerAnalysis::query()
                            ->join('customers', 'customers.id', '=', 'customer_analysis.customers_id')  // Join customers
                            ->join('analyze', 'analyze.id', '=', 'customer_analysis.analyze_id')  // Join analyze
                            ->selectRaw('CONCAT(customers.name_fa, " ", customers.family_fa) AS full_name, analyze.title AS analyze_title, customer_analysis.id')
                            ->get()
                            ->mapWithKeys(function ($item) {
                                // Combine the customer full name and analyze title
                                return [$item->id => $item->full_name . ' - ' . $item->analyze_title];
                            });
                    })
                    ->label('آنالیز مشتریان'),
            ])
            ->defaultSort('id', 'desc') // Remove the extra semicolon here
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
