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

class AnalysisTimeResource extends Resource
{
    protected static ?string $model = AnalysisTime::class;

    protected static ?string $navigationGroup = 'اطلاعات پایه';
    protected static ?string $pluralLabel = 'مدیریت زمان آنالیز ها';
    protected static ?string $navigationLabel = 'مدیریت زمان آنالیز ها';
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $label = 'مدیریت زمان آنالیز ها';
    
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Grid::make(2) // Creates a two-column layout
                ->schema([
                Radio::make('accordingto')
                    ->options([
                        0 => 'دقیقه',
                        1 => 'روز',
                    ])
                    ->label('برحسب')
                    ->inline()
                    ->required()
                    ->default(0)
                    ->reactive()
                    ->columnSpan(2),

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


                // String: number_done
                TextInput::make('number_done')
                    ->label('تعداد قابل انجام')
                    ->maxLength(5)
                    ->suffix(' عدد')
                    ->required()
                    ->numeric()
                    ->columnSpan([
                        'default' => 2,  // 1 column span on larger screens
                        'sm' => 1,       // 2 column span on small screens (screens smaller than 600px)
                    ]),

                // String: number_minutes
                TextInput::make('number_minutes')
                    ->label('دقیقه قابل انجام')
                    ->maxLength(5)
                    ->suffix(label: 'دقیقه')
                    ->nullable()
                    ->numeric()
                    ->columnSpan([
                        'default' => 2,  // 1 column span on larger screens
                        'sm' => 1,       // 2 column span on small screens (screens smaller than 600px)
                    ]),

                // Integer: default_number_day
                TextInput::make('default_number_day')
                    ->label('تعداد روز پیش فرض')
                    ->suffix('روز')
                    ->required()
                    ->numeric()
                    ->columnSpan([
                        'default' => 2,  // 1 column span on larger screens
                        'sm' => 1,       // 2 column span on small screens (screens smaller than 600px)
                    ]),
                ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                ->label('ردیف')
                ->sortable(),

                TextColumn::make('analyze_id')
                ->label('عنوان آنالیز')
                ->searchable()
                ->sortable()
                ->wrap()
                ->getStateUsing(function ($record) {
                    return $record->analyze ? $record->analyze->title : null;  // Use the title from the related model
                }),

                TextColumn::make('accordingto')
                ->label('برحسب')
                ->formatStateUsing(fn($state) => $state ? 'روز' : 'دقیقه')
                ->wrap()
                ->sortable(),
            
                TextColumn::make('number_done')
                ->label('تعداد قابل انجام')
                ->suffix(suffix: ' عدد')
                ->toggleable()
                ->wrap()
                ->sortable(),

                TextColumn::make('number_minutes')
                ->label('دقیقه قابل انجام')
                ->wrap()
                ->toggleable()
                ->sortable(),

                TextColumn::make('created_at')
                ->label('تاریخ ثبت')
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
