<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PriceAnalysisResource\Pages;
use App\Models\Analyze;
use App\Models\price_analysis;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Morilog\Jalali\Jalalian;
class PriceAnalysisResource extends Resource
{
    protected static ?string $model = price_analysis::class;

    protected static ?string $label = 'هزینه';

    protected static ?string $navigationIcon = 'heroicon-o-briefcase';

    public static function getNavigationGroup(): string
    {
        return __('filament.labels.base_info');
    }

    protected static ?string $navigationLabel = 'هزینه آنالیز مشتریان عادی';

    protected static ?string $pluralLabel = 'هزینه آنالیز مشتریان عادی';

    protected static ?string $singularLabel = 'هزینه ها';
    protected static ?int $navigationSort = 8;
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('analyze_id')
                    ->label('آنالیز')
                    ->options(Analyze::all()->pluck('title', 'id')) 
                    ->searchable()
                    ->required()
                    ->placeholder('انتخاب آنالیز'),

                TextInput::make('price')
                    ->label('قیمت(ریال)')
                    ->required()
                    ->maxLength(10),

                TextInput::make('date')
                    ->label('تاریخ ثبت')
                    ->maxLength(10)
                    ->required()
                    ->default(now()->format('Y-m-d')) 
                    ->hidden(), 
                TextInput::make('time')
                    ->label('زمان ثبت')
                    ->maxLength(10)
                    ->required()
                    ->default(now()->format('H:i:s')) 
                    ->hidden(), 
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
                TextColumn::make('analyze.title')
                ->label('آنالیز')
                ->sortable()
                ->searchable(),

                // TextColumn::make('created_at')
                //     ->label(__('filament.labels.created_at'))
                //     ->formatStateUsing(fn ($state) => 
                //         app()->getLocale() === 'fa' 
                //             ? Jalalian::fromDateTime($state)->format('Y/m/d H:i') // Convert to Jalali
                //             : \Carbon\Carbon::parse($state)->format('Y-m-d H:i') // Gregorian format
                //     )
                //     ->sortable(),
                TextColumn::make('date')
                ->label('تاریخ ثبت')
                ->sortable()
                ->searchable(),
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
            'index' => Pages\ListPriceAnalyses::route('/'),
            'create' => Pages\CreatePriceAnalysis::route('/create'),
            'edit' => Pages\EditPriceAnalysis::route('/{record}/edit'),
        ];
    }
}
