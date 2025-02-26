<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PaymentAnalyzeResource\Pages;
use App\Models\CustomerAnalysis;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\App;
use Morilog\Jalali\Jalalian;
use Carbon\Carbon;
use Filament\Tables\Columns\TextColumn;

class PaymentAnalyzeResource extends Resource
{
    protected static ?string $model = CustomerAnalysis::class;
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationGroup = 'امور مالی';


    // Use translation function
    public static function getNavigationLabel(): string
    {
        return __('filament.labels.financial_check_management');
    }

    public static function getPluralLabel(): string
    {
        return __('filament.labels.financial_check_management');
    }

    public static function getLabel(): string
    {
        return __('filament.labels.financial_check_management');
    }
    

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('id')->label(__('filament.labels.row'))->disabled(),
                Forms\Components\TextInput::make('customer.name_fa')
                    ->label(__('filament.labels.customer_name'))
                    ->disabled()
                    ->formatStateUsing(fn ($state, $record) => $record->customer->name_fa . ' ' . $record->customer->family_fa),
                Forms\Components\TextInput::make('acceptance_date')
                    ->label(__('filament.labels.acceptance_date'))
                    ->disabled()
                    ->formatStateUsing(fn ($state) => Jalalian::fromCarbon(Carbon::parse($state))->format('Y/m/d')),
                Forms\Components\TextInput::make('analyze.title')
                    ->label(__('filament.labels.analyze'))
                    ->disabled(),
                Forms\Components\TextInput::make('total_cost')
                    ->label(__('filament.labels.total_cost'))
                    ->disabled(),
                Forms\Components\TextInput::make('tracking_code')
                    ->label(__('filament.labels.tracking_code'))
                    ->disabled(),
                Forms\Components\TextInput::make('status')
                    ->label(__('filament.labels.acceptance_status'))
                    ->disabled()
                    ->formatStateUsing(fn ($state) => match ($state) {
                        0 => __('filament.labels.awaiting_payment'),
                        1 => __('filament.labels.full_acceptance'),
                        2 => __('filament.labels.awaiting'),
                        3 => __('filament.labels.canceled'),
                        4 => __('filament.labels.financial_approval'),
                        5 => __('filament.labels.awaiting_technical_management_approval'),
                        6 => __('filament.labels.technical_management_approval'),
                        7 => __('filament.labels.analysis_complete'),
                        8 => __('filament.labels.awaiting_financial_management_approval'),
                        default => __('filament.labels.unknown'),
                    }),
                Forms\Components\TextInput::make('samples_number')
                    ->label(__('filament.labels.samples_number'))
                    ->disabled(),
                Forms\Components\TextInput::make('analyze_time')
                    ->label(__('filament.labels.analysis_time'))
                    ->disabled(),
                Forms\Components\TextInput::make('applicant_share')
                    ->label(__('filament.labels.applicant_share'))
                    ->disabled(),
                Forms\Components\TextInput::make('network_share')
                    ->label(__('filament.labels.network_share'))
                    ->disabled(),
                Forms\Components\TextInput::make('network_id')
                    ->label(__('filament.labels.network_id'))
                    ->disabled(),
                Forms\Components\TextInput::make('payment_method_id')
                    ->label(__('filament.labels.payment_method'))
                    ->disabled(),
                Forms\Components\TextInput::make('description')
                    ->label(__('filament.labels.acceptance_description'))
                    ->disabled(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')->label(__('filament.labels.row')),
                TextColumn::make('customer.name_fa')
                    ->label(__('filament.labels.customer_name'))
                    ->formatStateUsing(fn ($state, $record) => $record->customer->name_fa . ' ' . $record->customer->family_fa),
                TextColumn::make('acceptance_date')
                    ->label(__('filament.labels.acceptance_date'))
                    ->dateTime()
                    ->unless(App::isLocale('en'), fn (TextColumn $column) => $column->jalaliDate()),
                TextColumn::make('analyze.title')
                    ->label(__('filament.labels.analyze')),
                TextColumn::make('total_cost')
                    ->label(__('filament.labels.total_cost')),
                TextColumn::make('status')
                    ->label(__('filament.labels.acceptance_status'))
                    ->formatStateUsing(fn ($state) => match ($state) {
                        0 => __('filament.labels.awaiting_payment'),
                        1 => __('filament.labels.full_acceptance'),
                        2 => __('filament.labels.awaiting'),
                        3 => __('filament.labels.canceled'),
                        4 => __('filament.labels.financial_approval'),
                        5 => __('filament.labels.awaiting_technical_management_approval'),
                        6 => __('filament.labels.technical_management_approval'),
                        7 => __('filament.labels.analysis_complete'),
                        8 => __('filament.labels.awaiting_financial_management_approval'),
                        default => __('filament.labels.unknown'),
                    }),
                TextColumn::make('tracking_code')
                    ->label(__('filament.labels.tracking_code')),
                
                    TextColumn::make('created_at')
                    ->label(__('filament.labels.created_at'))
                    ->formatStateUsing(fn ($state) => 
                        app()->getLocale() === 'fa' 
                            ? Jalalian::fromDateTime($state)->format('Y/m/d H:i') // Convert to Jalali
                            : Carbon::parse($state)->format('Y-m-d H:i') // Gregorian format
                    )
                    ->sortable(),
            ])
            ->filters([])
            ->actions([
                Tables\Actions\ViewAction::make(),
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
        ];
    }
}
