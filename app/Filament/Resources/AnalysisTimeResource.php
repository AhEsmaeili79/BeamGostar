<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AnalysisTimeResource\Pages;
use App\Models\AnalysisTime;
use App\Models\Analyze;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Radio;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\Grid;
use Morilog\Jalali\Jalalian;
class AnalysisTimeResource extends Resource
{
    protected static ?string $model = AnalysisTime::class;

    // Make methods public to override parent methods
    public static function getNavigationGroup(): string
    {
        return __('filament.labels.base_info');
    }

    public static function getPluralLabel(): string
    {
        return __('filament.labels.analysis_time_management');
    }

    public static function getNavigationLabel(): string
    {
        return __('filament.labels.analysis_time_management');
    }

    public static function getLabel(): string
    {
        return __('filament.labels.analysis_time_management');
    }

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    
    protected static ?int $navigationSort = 4;
    
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Grid::make(2) 
                ->schema([

                Radio::make('accordingto')
                    ->options([
                        0 => __('filament.labels.minute'),
                        1 => __('filament.labels.day'),
                    ])
                    ->label(__('filament.labels.based_on'))
                    ->inline()
                    ->required()
                    ->default(0)
                    ->reactive()
                    ->columnSpan(2),

                Select::make('analyze_id')
                    ->label(__('filament.labels.analysis'))
                    ->options(
                        Analyze::whereNotNull('title')  
                            ->pluck('title', 'id') 
                    )
                    ->unique(AnalysisTime::class, 'analyze_id') // Enforce unique validation for the title
                    ->required()
                    ->searchable()
                    ->columnSpan([ 
                        'default' => 2,  
                        'sm' => 1,      
                    ]),

                TextInput::make('number_done')
                    ->label(__('filament.labels.number_possible'))
                    ->maxLength(5)
                    ->suffix(__('filament.labels.units'))
                    ->required()
                    ->numeric()
                    ->columnSpan([ 
                        'default' => 2,  
                        'sm' => 1,       
                    ]),

                TextInput::make('number_minutes')
                    ->label(__('filament.labels.number_minutes_possible'))
                    ->maxLength(5)
                    ->suffix(__('filament.labels.minutes'))
                    ->nullable()
                    ->numeric()
                    ->columnSpan([ 
                        'default' => 2,  
                        'sm' => 1,       
                    ])
                    ->visible(fn ($get) => $get('accordingto') == 0),

                TextInput::make('default_number_day')
                    ->label(__('filament.labels.default_number_day'))
                    ->suffix(__('filament.labels.days'))
                    ->required()
                    ->numeric()
                    ->columnSpan([ 
                        'default' => 2,  
                        'sm' => 1,      
                    ]),

                ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([

                TextColumn::make('id')
                ->label(__('filament.labels.row'))
                ->sortable(),

                TextColumn::make('analyze_id')
                ->label(__('filament.labels.analysis_title'))
                ->searchable()
                ->sortable()
                ->wrap()
                ->getStateUsing(function ($record) {
                    return $record->analyze ? $record->analyze->title : null;  
                }),

                TextColumn::make('accordingto')
                ->label(__('filament.labels.based_on'))
                ->formatStateUsing(fn($state) => $state ? __('filament.labels.day') : __('filament.labels.minute'))
                ->wrap()
                ->sortable(),

                TextColumn::make('number_done')
                ->label(__('filament.labels.number_possible'))
                ->suffix(__('filament.labels.units'))
                ->toggleable()
                ->wrap()
                ->sortable(),

                TextColumn::make('number_minutes')
                ->label(__('filament.labels.number_minutes_possible'))
                ->wrap()
                ->toggleable()
                ->sortable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label(__('filament.labels.created_at'))
                    ->formatStateUsing(fn ($state) => 
                        app()->getLocale() === 'fa' 
                            ? Jalalian::fromDateTime($state)->format('Y/m/d H:i') // Convert to Jalali
                            : \Carbon\Carbon::parse($state)->format('Y-m-d H:i') // Gregorian format
                    )
                    ->sortable(),
            ])
            ->filters([ // filters
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
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
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAnalysisTimes::route('/'),
            'create' => Pages\CreateAnalysisTime::route('/create'),
            'edit' => Pages\EditAnalysisTime::route('/{record}/edit'),
        ];
    }
}
