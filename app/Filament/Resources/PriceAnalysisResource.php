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

class PriceAnalysisResource extends Resource
{
    protected static ?string $model = price_analysis::class;

    protected static ?string $label = 'هزینه';

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'اطلاعات پایه';

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
                    ->options(Analyze::all()->pluck('title', 'id')) // Fetch all Analyze titles
                    ->searchable()
                    ->required()
                    ->placeholder('انتخاب آنالیز'),

                // Price field
                TextInput::make('price')
                    ->label('قیمت(ریال)')
                    ->required()
                    ->maxLength(10),

                // Date field
                TextInput::make('date')
                    ->label('تاریخ ثبت')
                    ->nullable()
                    ->maxLength(10),

                // Time field
                TextInput::make('time')
                    ->label('زمان ثبت')
                    ->nullable()
                    ->maxLength(10),
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

                TextColumn::make('price')
                    ->label('قیمت(ریال)')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('date')
                    ->label('تاریخ ثبت')
                    ->sortable(),

                TextColumn::make('time')
                    ->label('زمان ثبت')
                    ->sortable(),

                TextColumn::make('created_at')
                    ->label('تاریخ ثبت')
                    ->formatStateUsing(fn ($state) => \Carbon\Carbon::parse($state)->diffForHumans())
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
