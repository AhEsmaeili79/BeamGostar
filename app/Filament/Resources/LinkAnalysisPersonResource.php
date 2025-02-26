<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LinkAnalysisPersonResource\Pages;
use App\Models\Customers;
use App\Models\LinkAnalysisPerson;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Morilog\Jalali\Jalalian;

class LinkAnalysisPersonResource extends Resource
{
    protected static ?string $model = LinkAnalysisPerson::class;
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationGroup = 'اطلاعات پایه';
    protected static ?int $navigationSort = 6;

    // Move dynamic translations to methods
    public static function getLabel(): string
    {
        return __('filament.labels.link_analysis_person_management');
    }

    public static function getNavigationLabel(): string
    {
        return __('filament.labels.link_analysis_person_management');
    }

    public static function getPluralLabel(): string
    {
        return __('filament.labels.link_analysis_person_management');
    }

    public static function getSingularLabel(): string
    {
        return __('filament.labels.analyzer');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('date')
                    ->label(__('filament.labels.registration_date'))
                    ->maxLength(10)
                    ->required()
                    ->default(now()->format('Y-m-d')) 
                    ->hidden(),

                TextInput::make('time')
                    ->label(__('filament.labels.registration_time'))
                    ->maxLength(10)
                    ->required()
                    ->default(now()->format('H:i:s'))
                    ->hidden(),

                Select::make('customers_id')
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

                Select::make('analyze_id')
                    ->label(__('filament.labels.analysis'))
                    ->relationship('analyze', 'title')
                    ->required(),
            ]);
    }

    public static function create(Form $form, array $data)
    {
        $data['date'] = now()->format('Y-m-d');
        $data['time'] = now()->format('H:i:s');

        return parent::create($form, $data);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('customer.name_fa')
                    ->label(__('filament.labels.customer_name'))
                    ->formatStateUsing(fn($state, $record) => $record->customer->name_fa . ' ' . $record->customer->family_fa),

                TextColumn::make('analyze_id')
                    ->label(__('filament.labels.analysis_title'))
                    ->searchable()
                    ->sortable()
                    ->wrap()
                    ->getStateUsing(fn($record) => $record->analyze ? $record->analyze->title : null),

                TextColumn::make('created_at')
                    ->label(__('filament.labels.created_at'))
                    ->formatStateUsing(fn ($state) => 
                        app()->getLocale() === 'fa' 
                            ? Jalalian::fromDateTime($state)->format('Y/m/d H:i') // Convert to Jalali
                            : \Carbon\Carbon::parse($state)->format('Y-m-d H:i') // Gregorian format
                    )
                    ->sortable(),
            ])
            ->filters([
                //
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
            'index' => Pages\ListLinkAnalysisPeople::route('/'),
            'create' => Pages\CreateLinkAnalysisPerson::route('/create'),
            'edit' => Pages\EditLinkAnalysisPerson::route('/{record}/edit'),
        ];
    }
}
