<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RejectResource\Pages;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use App\Models\CustomerAnalysis;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Support\Facades\App;
use Morilog\Jalali\Jalalian;
use Carbon\Carbon;
use Filament\Tables\Actions\Action;
class RejectResource extends Resource
{
    protected static ?string $model = CustomerAnalysis::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    // Move dynamic expressions like this one into methods
    protected static ?string $navigationLabel = null; // to be set by a method


    protected static ?string $pluralModelLabel = null; // to be set by a method

    protected static ?string $label = null; // to be set by a method

    protected static ?string $pluralLabel = null; // to be set by a method

    protected static ?string $singularLabel = null; // to be set by a method

    protected static ?string $slug = 'rejects';
    
    protected static ?int $navigationSort = 4;

    // Method to set navigation label dynamically
    public static function getNavigationGroup(): string
    {
        return __('filament.labels.admissions');  // Make sure you add this translation to the files
    }

    public static function getNavigationLabel(): string
    {
        return __('filament.labels.reject');
    }

    public static function getPluralModelLabel(): string
    {
        return __('filament.labels.reject');
    }

    public static function getLabel(): string
    {
        return __('filament.labels.reject');
    }

    public static function getPluralLabel(): string
    {
        return __('filament.labels.reject');
    }

    public static function getSingularLabel(): string
    {
        return __('filament.labels.reject');
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
                        9 => __('filament.labels.reject_requrst'),
                        10 => __('filament.labels.approved_reject'),
                        11 => __('filament.labels.deny_reject'),
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
        ->query(CustomerAnalysis::query()->whereIn('status', [9, 10, 11]))  // Only show rows with status 9, 10, or 11
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
                    9 => __('filament.labels.reject_requrst'),
                    10 => __('filament.labels.approved_reject'),
                    11 => __('filament.labels.deny_reject'),
                    default => __('filament.labels.unknown'),
                }),

                TextColumn::make('id')->label(__('filament.labels.row')),
                TextColumn::make('tracking_code')->label(__('filament.labels.tracking_code')),
                TextColumn::make('customer.name_fa')
                    ->label(__('filament.labels.customer_name'))
                    ->formatStateUsing(fn ($state, $record) => $record->customer->name_fa . ' ' . $record->customer->family_fa),
                    TextColumn::make('status')
                    ->label(__('filament.labels.acceptance_status'))
                    ->formatStateUsing(fn ($state) => match ($state) {
                        'requested' => 'درخواست عودت',
                        'ready' => 'آماده جهت عودت',
                        'returned' => 'عودت شده',
                        default => 'نامشخص',
                    }),
                TextColumn::make('tracking_code')
                    ->label(__('filament.labels.tracking_code')),
            ])
            ->filters([
                Filter::make('status')
                    ->label(__('filament.labels.acceptance_status'))
                    ->form([
                        Forms\Components\Select::make('status')
                            ->label(__('filament.labels.status'))
                            ->options([
                                0 => __('filament.labels.awaiting_payment'),
                                1 => __('filament.labels.full_acceptance'),
                                2 => __('filament.labels.awaiting'),
                                3 => __('filament.labels.canceled'),
                                4 => __('filament.labels.financial_approval'),
                                5 => __('filament.labels.awaiting_technical_management_approval'),
                                6 => __('filament.labels.technical_management_approval'),
                                7 => __('filament.labels.analysis_complete'),
                                8 => __('filament.labels.awaiting_financial_management_approval'),
                                9 => __('filament.labels.reject_requrst'),
                                10 => __('filament.labels.approved_reject'),
                                11 => __('filament.labels.deny_reject'),
                            ]),
                    ]),
            ])
            ->actions([
                Action::make('approve')
                ->label(__('filament.labels.approve'))
                ->visible(fn ($record) => $record->status == 9)
                ->action(function ($record) {
                    $record->update(['status' => 10]);
                })
                ->color('success'),

                Action::make('deny')
                ->label(__('filament.labels.deny'))
                ->visible(fn ($record) => $record->status == 9)
                ->action(function ($record) {
                    $record->update(['status' => 11]);
                })
                ->color('danger'),
                Tables\Actions\ViewAction::make(),
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
            'index' => Pages\ListRejects::route('/'),
        ];
    }
}
