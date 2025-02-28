<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LinkAnalysisPersonResource\Pages;
use App\Models\LinkAnalysisPerson;
use App\Models\Personnel;
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
    protected static ?string $navigationIcon = 'heroicon-o-link';

    public static function getNavigationGroup(): string
    {
        return __('filament.labels.base_info');
    }
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
                Select::make('personnel_id')
                ->label(__('filament.labels.operator'))
                ->options(
                    Personnel::whereNotNull('name')
                        ->whereNotNull('family')
                        ->whereHas('user', function($query) {
                            $query->whereHas('roles', function($query) {
                                $query->whereIn('name', ['آزمایشگاه', 'ازمایشگاه']);
                            });
                        })
                        ->get()
                        ->mapWithKeys(function ($personnel) {
                            return [$personnel->id => $personnel->name . ' ' . $personnel->family];
                        })
                )
                ->required()
                ->searchable(),
            

                Select::make('analyze_id')
                    ->label(__('filament.labels.analysis'))
                    ->relationship('analyze', 'title')
                    ->required(),
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
                TextColumn::make('personnel_id')
                    ->label(__('filament.labels.operator'))
                    ->formatStateUsing(fn($state, $record) => $record->personnel->name . ' ' . $record->personnel->family),

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
                TextColumn::make('updated_at')
                    ->label(__('filament.labels.updated_at'))
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
