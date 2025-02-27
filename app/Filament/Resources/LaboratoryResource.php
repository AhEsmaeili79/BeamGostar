<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LaboratoryResource\Pages;
use App\Models\Laboratory;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Ysfkaya\FilamentPhoneInput\Tables\PhoneColumn;
use Ysfkaya\FilamentPhoneInput\PhoneInputNumberType;
use Illuminate\Support\Facades\App;

class LaboratoryResource extends Resource
{
    protected static ?string $model = Laboratory::class;
    protected static ?string $navigationIcon = 'heroicon-o-presentation-chart-line';
    protected static ?int $navigationSort = 5;
    
    // Removed dynamic expressions from these properties
    protected static ?string $navigationGroup = 'آزمایشگاه';

    // These are moved to methods
    protected static ?string $navigationLabel = null; 
    protected static ?string $pluralLabel = null;
    protected static ?string $label = null;

    public static function getLabel(): string
    {
        return __('filament.labels.laboratory');
    }

    public static function getNavigationLabel(): string
    {
        return __('filament.labels.laboratory_operator');
    }

    public static function getPluralLabel(): string
    {
        return __('filament.labels.laboratory_operators');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('full_name_fa')
                    ->label(__('filament.labels.full_name_fa'))
                    ->maxLength(81),
                Forms\Components\TextInput::make('full_name_en')
                    ->label(__('filament.labels.full_name_en'))
                    ->maxLength(81),
                Forms\Components\TextInput::make('national_code')
                    ->label(__('filament.labels.national_code'))
                    ->required()
                    ->maxLength(6),
                Forms\Components\TextInput::make('national')
                    ->label(__('filament.labels.national'))
                    ->required()
                    ->maxLength(6),
                Forms\Components\TextInput::make('mobile')
                    ->label(__('filament.labels.mobile'))
                    ->required()
                    ->maxLength(11),
                Forms\Components\TextInput::make('customer_type')
                    ->label(__('filament.labels.customer_type'))
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('clearing_type')
                    ->label(__('filament.labels.clearing_type'))
                    ->required()
                    ->numeric(),
                Forms\Components\DatePicker::make('acceptance_date')
                    ->label(__('filament.labels.acceptance_date'))
                    ->required(),
                Forms\Components\TextInput::make('samples_number')
                    ->label(__('filament.labels.samples_number'))
                    ->required()
                    ->numeric()
                    ->default(0),
                Forms\Components\TextInput::make('analyze_id')
                    ->label(__('filament.labels.analyze_id'))
                    ->numeric()
                    ->default(0),
                Forms\Components\TextInput::make('tracking_code')
                    ->label(__('filament.labels.tracking_code'))
                    ->maxLength(20),
                Forms\Components\Textarea::make('scan_form')
                    ->label(__('filament.labels.scan_form'))
                    ->columnSpanFull(),
                Forms\Components\Textarea::make('description')
                    ->label(__('filament.labels.description'))
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('priority')
                    ->label(__('filament.labels.priority'))
                    ->numeric(),
                Forms\Components\TextInput::make('status')
                    ->label(__('filament.labels.status'))
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('state')
                    ->label(__('filament.labels.internal_status'))
                    ->numeric(),
                Forms\Components\TextInput::make('date_success')
                    ->label(__('filament.labels.success_date'))
                    ->maxLength(16),
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
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('full_name_fa')
                    ->label(__('filament.labels.full_name_fa'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('full_name_en')
                    ->label(__('filament.labels.full_name_en'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('national')
                    ->label(__('filament.labels.national'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('national_code')
                    ->label(__('filament.labels.national_code'))
                    ->searchable(),
                PhoneColumn::make('mobile')
                    ->displayFormat(PhoneInputNumberType::NATIONAL)
                    ->label(__('filament.labels.mobile'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('customer_type')
                    ->label(__('filament.labels.customer_type'))
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('clearing_type')
                    ->label(__('filament.labels.clearing_type'))
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('acceptance_date')
                    ->label(__('filament.labels.acceptance_date'))
                    ->dateTime()
                    ->unless(App::isLocale('en'), fn (Tables\Columns\TextColumn $column) => $column->jalaliDate())
                    ->sortable(),
                Tables\Columns\TextColumn::make('samples_number')
                    ->label(__('filament.labels.samples_number'))
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('analyze_id')
                    ->label(__('filament.labels.analyze_id'))
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('tracking_code')
                    ->label(__('filament.labels.tracking_code'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('priority')
                    ->label(__('filament.labels.priority'))
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->label(__('filament.labels.acceptance_status'))
                    ->formatStateUsing(fn ($state) => match ($state) {
                        0 => __('filament.status.waiting_for_payment'),
                        1 => __('filament.status.full_acceptance'),
                        2 => __('filament.status.awaiting'),
                        3 => __('filament.status.cancelled'),
                        4 => __('filament.status.financial_approval'),
                        5 => __('filament.status.waiting_for_technical_management_approval'),
                        6 => __('filament.status.technical_management_approved'),
                        7 => __('filament.status.analysis_completed'),
                        8 => __('filament.status.waiting_for_financial_management_approval'),
                        default => __('filament.status.unknown'),
                    }),
                Tables\Columns\TextColumn::make('state')
                    ->label(__('filament.labels.internal_status'))
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('date_success')
                    ->label(__('filament.labels.success_date'))
                    ->dateTime()
                    ->unless(App::isLocale('en'), fn (Tables\Columns\TextColumn $column) => $column->jalaliDate())
                    ->searchable(),
            ])
            ->filters([ 
                // Add any filters you need here
            ])
            ->actions([
                Tables\Actions\ViewAction::make()->label(__('filament.actions.view')),
            ])
            ->bulkActions([]); // No bulk actions for now
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
            'index' => Pages\ListLaboratories::route('/'),
        ];
    }
}
