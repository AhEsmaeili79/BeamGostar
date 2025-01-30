<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PaymentAnalyzeResource\Pages;
use App\Filament\Resources\PaymentAnalyzeResource\RelationManagers;
use App\Models\PaymentAnalyze;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PaymentAnalyzeResource extends Resource
{
    protected static ?string $model = PaymentAnalyze::class;

    
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationLabel = 'مدیریت پرداخت مشتریان';

    protected static ?string $pluralLabel = 'مدیریت مالی';

    protected static ?string $modelLabel = 'چک مالی';

    protected static ?string $navigationGroup = 'امور مالی';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('customer_analysis_id')
                ->options(function () {
                    return \App\Models\CustomerAnalysis::query()
                        ->join('customers', 'customers.id', '=', 'customer_analysis.customers_id')  // اتصال به جدول مشتریان
                        ->join('analyze', 'analyze.id', '=', 'customer_analysis.analyze_id')  // اتصال به جدول آنالیز
                        ->selectRaw('CONCAT(customers.name_fa, " ", customers.family_fa) AS full_name, analyze.title AS analyze_title, customer_analysis.id')
                        ->get()
                        ->mapWithKeys(function ($item) {
                            return [$item->id => $item->full_name . ' - ' . $item->analyze_title];
                        });
                })
                ->required()
                ->label('آنالیز مشتریان'),
                Forms\Components\TextInput::make('upload_fish')
                    ->label('آپلود فیش پرداخت')
                    ->nullable(),
                Forms\Components\TextInput::make('transaction_id')
                    ->label('شناسه تراکنش')
                    ->nullable(),
                Forms\Components\TextInput::make('uniq_id')
                    ->label('شناسه')
                    ->nullable(),
                Forms\Components\TextInput::make('datepay')
                    ->label('تاریخ پرداخت')
                    ->required()
                    ->placeholder('مثال: 1401/04/13'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('شناسه')
                    ->sortable(),
                Tables\Columns\TextColumn::make('customerAnalysis')
                    ->label('آنالیز مشتریان')
                    ->getStateUsing(function ($record) {
                        return $record->customerAnalysis->customer->name_fa . ' ' . $record->customerAnalysis->customer->family_fa . ' - ' . $record->customerAnalysis->analyze->title;
                    }),
                Tables\Columns\TextColumn::make('upload_fish')
                    ->label('فیش پرداخت')
                    ->limit(20),
                Tables\Columns\TextColumn::make('transaction_id')
                    ->label('شناسه تراکنش'),
                Tables\Columns\TextColumn::make('uniq_id')
                    ->label('شناسه'),
                Tables\Columns\TextColumn::make('datepay')
                    ->label('تاریخ پرداخت'),
            ])
            ->filters([

            ])
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
            'index' => Pages\ListPaymentAnalyzes::route('/'),
            'create' => Pages\CreatePaymentAnalyze::route('/create'),
            'edit' => Pages\EditPaymentAnalyze::route('/{record}/edit'),
        ];
    }
}
