<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AnalysisDelayResource\Pages;
use App\Models\AnalysisDelay;
use App\Models\Analyze;
use Filament\Forms;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class AnalysisDelayResource extends Resource
{
    protected static ?string $model = AnalysisDelay::class;
    protected static ?string $navigationGroup = 'مدیریت آنالیز ها';
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationLabel = ' تاخیر آنالیز ها';
    protected static ?string $pluralLabel = 'تاخیر آنالیز ها';


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Grid::make(2) // Creates a two-column layout
                ->schema([
                    Select::make('analyze_id')
                    ->label('آنالیز')
                    ->options(
                        Analyze::whereNotNull('title')  // Ensure 'title' is not null
                            ->whereNotNull('title')   // Ensure 'name' is not null
                            ->pluck('title', 'id') // Assuming 'title' is the key and 'name' is the value
                    )
                    ->required()
                    ->searchable()
                    ->columnSpan([
                        'default' => 2,  // 1 column span on larger screens
                        'sm' => 1,       // 2 column span on small screens (screens smaller than 600px)
                    ]),
            
                    Forms\Components\TextInput::make('delay')
                    ->label('تاخیر برحسب روز')
                    ->numeric()
                    ->suffix('روز')
                    ->required()
                    ->columnSpan([
                        'default' => 2,  // 1 column span on larger screens
                        'sm' => 1,       // 2 column span on small screens (screens smaller than 600px)
                    ]),

                Forms\Components\Textarea::make('text')
                    ->label('متن پیامک')
                    ->required()
                    ->columnSpan(2),
                ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                ->label('ردیف')
                ->sortable(),

                Tables\Columns\TextColumn::make('analyze_id')
                ->label('عنوان آنالیز')
                ->searchable()
                ->wrap()
                ->sortable(),
                
                Tables\Columns\TextColumn::make('delay')
                ->label('تاخیر برحسب روز')
                ->suffix(' روز')
                ->wrap()
                ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                ->label('تاریخ ایجاد')
                ->wrap()
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
                    Tables\Actions\DeleteBulkAction::make()
                    ->icon('heroicon-o-arrow-down-on-square'),
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
            'index' => Pages\ListAnalysisDelays::route('/'),
            'create' => Pages\CreateAnalysisDelay::route('/create'),
            'edit' => Pages\EditAnalysisDelay::route('/{record}/edit'),
        ];
    }
    
}
