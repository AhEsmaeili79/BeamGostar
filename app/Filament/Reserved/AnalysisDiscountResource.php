<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AnalysisDiscountResource\Pages;
use App\Filament\Resources\AnalysisDiscountResource\RelationManagers;
use App\Models\analysis_discount;
use Filament\Forms;
use App\Models\Analyze;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class AnalysisDiscountResource extends Resource
{
    protected static ?string $model = analysis_discount::class;

    protected static ?string $navigationGroup = 'مدیریت آنالیز ها';
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationLabel = 'تخفیف آنالیز ها';
    protected static ?string $pluralLabel ='تخفیف آنالیز ها';
    protected static ?string $label = 'تخفیف';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('analyze_id')
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
                Forms\Components\TextInput::make('discount_type')->required(),
                Forms\Components\TextInput::make('cent')->nullable(),
                Forms\Components\TextInput::make('amount')->nullable(),
                Forms\Components\TextInput::make('date')->nullable(),
                Forms\Components\TextInput::make('time')->nullable(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')->sortable(),
                Tables\Columns\TextColumn::make('analyze_id')->sortable(),
                Tables\Columns\TextColumn::make('discount_type')->sortable(),
                Tables\Columns\TextColumn::make('cent'),
                Tables\Columns\TextColumn::make('amount'),
                Tables\Columns\TextColumn::make('date'),
                Tables\Columns\TextColumn::make('time'),
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
            'index' => Pages\ListAnalysisDiscounts::route('/'),
            'create' => Pages\CreateAnalysisDiscount::route('/create'),
            'edit' => Pages\EditAnalysisDiscount::route('/{record}/edit'),
        ];
    }
}
