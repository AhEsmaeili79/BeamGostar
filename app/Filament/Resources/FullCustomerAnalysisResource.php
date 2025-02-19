<?php

namespace App\Filament\Resources;

use App\Filament\Resources\FullCustomerAnalysisResource\Pages;
use App\Models\CustomerAnalysis;
use App\Models\Customers;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\App;

class FullCustomerAnalysisResource extends Resource
{
    protected static ?string $model = CustomerAnalysis::class;

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->where('status', 1);
    }

    public static function getNavigationLabel(): string
    {
        return __('filament.labels.full_customer_analysis_management');
    }

    public static function getPluralLabel(): string
    {
        return __('filament.labels.full_customer_analysis_management');
    }

    public static function getLabel(): string
    {
        return __('filament.labels.full_customer_analysis_management');
    }

    protected static ?string $navigationGroup = 'پذیرش';
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('customers_id')
                    ->label(__('filament.labels.customer'))
                    ->options(
                        Customers::whereNotNull('name_fa')
                            ->whereNotNull('family_fa')
                            ->get()
                            ->mapWithKeys(function ($customer) {
                                return [$customer->id => $customer->name_fa . ' ' . $customer->family_fa];
                            })
                    )
                    ->required()
                    ->searchable(),
                Forms\Components\DatePicker::make('acceptance_date')
                    ->label(__('filament.labels.acceptance_date'))
                    ->required(),
                Forms\Components\Select::make('get_answers_id')
                    ->label(__('filament.labels.answer_method'))
                    ->relationship('getAnswers', 'title')
                    ->required(),
                Forms\Components\Select::make('analyze_id')
                    ->label(__('filament.labels.analysis'))
                    ->relationship('analyze', 'title')
                    ->required(),
                Forms\Components\TextInput::make('samples_number')
                    ->label(__('filament.labels.sample_number'))
                    ->numeric()
                    ->required(),
                Forms\Components\TextInput::make('analyze_time')
                    ->label(__('filament.labels.analysis_time'))
                    ->required(),
                Forms\Components\Toggle::make('value_added')
                    ->label(__('filament.labels.value_added')),
                Forms\Components\Toggle::make('grant')
                    ->label(__('filament.labels.grant')),
                Forms\Components\TextInput::make('additional_cost')
                    ->label(__('filament.labels.additional_cost')),
                Forms\Components\TextInput::make('additional_cost_text')
                    ->label(__('filament.labels.additional_cost_description')),
                Forms\Components\TextInput::make('total_cost')
                    ->label(__('filament.labels.total_cost'))
                    ->required(),
                Forms\Components\TextInput::make('applicant_share')
                    ->label(__('filament.labels.applicant_share'))
                    ->required(),
                Forms\Components\TextInput::make('network_share')
                    ->label(__('filament.labels.network_share'))
                    ->required(),
                Forms\Components\TextInput::make('network_id')
                    ->label(__('filament.labels.network_id'))
                    ->required(),
                Forms\Components\Select::make('payment_method_id')
                    ->label(__('filament.labels.payment_method'))
                    ->relationship('paymentMethod', 'title')
                    ->required(),
                Forms\Components\Toggle::make('discount')
                    ->label(__('filament.labels.discount')),
                Forms\Components\TextInput::make('discount_num')
                    ->label(__('filament.labels.discount_amount')),
                Forms\Components\FileUpload::make('scan_form')
                    ->label(__('filament.labels.scan_form'))
                    ->directory('uploads/scan_forms') // Save files to a specific directory
                    ->required(),
                Forms\Components\Textarea::make('description')
                    ->label(__('filament.labels.acceptance_description')),
                Forms\Components\Toggle::make('priority')
                    ->label(__('filament.labels.priority')),
                Forms\Components\Select::make('status')
                    ->label(__('filament.labels.status'))
                    ->options([
                        0 => __('filament.labels.waiting_for_payment'),
                        1 => __('filament.labels.full_acceptance'),
                        2 => __('filament.labels.pending'),
                        3 => __('filament.labels.canceled'),
                        4 => __('filament.labels.financial_approval'),
                        5 => __('filament.labels.waiting_for_technical_approval'),
                        8 => __('filament.labels.waiting_for_financial_approval'),
                    ])
                    ->required(),
                Forms\Components\TextInput::make('tracking_code')
                    ->label(__('filament.labels.tracking_code')),
                Forms\Components\DatePicker::make('date_answer')
                    ->label(__('filament.labels.answer_date')),
                Forms\Components\DatePicker::make('upload_answer')
                    ->label(__('filament.labels.upload_date')),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label(__('filament.labels.row')),

                Tables\Columns\TextColumn::make('customer.name_fa')
                    ->label(__('filament.labels.customer'))
                    ->formatStateUsing(function ($state, $record) {
                        return $record->customer->name_fa . ' ' . $record->customer->family_fa;
                    }),

                Tables\Columns\TextColumn::make('analyze.title')
                    ->label(__('filament.labels.analysis')),

                Tables\Columns\TextColumn::make('total_cost')
                    ->label(__('filament.labels.total_cost')),

                Tables\Columns\TextColumn::make('status')
                    ->label(__('filament.labels.status'))
                    ->formatStateUsing(fn ($state) => match ($state) {
                        0 => __('filament.labels.waiting_for_payment'),
                        1 => __('filament.labels.full_acceptance'),
                        2 => __('filament.labels.pending'),
                        3 => __('filament.labels.canceled'),
                        4 => __('filament.labels.financial_approval'),
                        5 => __('filament.labels.waiting_for_technical_approval'),
                        8 => __('filament.labels.waiting_for_financial_approval'),
                        default => __('filament.labels.unknown'),
                    }),

                Tables\Columns\TextColumn::make('tracking_code')
                    ->label(__('filament.labels.tracking_code')),

                Tables\Columns\TextColumn::make('acceptance_date')
                    ->label(__('filament.labels.acceptance_date'))
                    ->dateTime()
                    ->unless(App::isLocale('en'), fn (Tables\Columns\TextColumn $column) => $column->jalaliDate()),
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
        return [
            // Add relations if needed
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListFullCustomerAnalyses::route('/'),
            'edit' => Pages\EditFullCustomerAnalysis::route('/{record}/edit'),
        ];
    }
}
