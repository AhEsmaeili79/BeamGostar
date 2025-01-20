<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LinkAnalysisPersonResource\Pages;
use App\Filament\Resources\LinkAnalysisPersonResource\RelationManagers;
use App\Models\Customers;
use App\Models\LinkAnalysisPerson;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class LinkAnalysisPersonResource extends Resource
{
    protected static ?string $model = LinkAnalysisPerson::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationLabel = 'Link Analysis Persons';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('date')
                    ->label('تاریخ ثبت')
                    ->maxLength(10)
                    ->required(),
                TextInput::make('time')
                    ->label('زمان ثبت')
                    ->maxLength(10)
                    ->required(),
                Select::make('customers_id')
                    ->label('مشتری')
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
                    ->label('آنالیز')
                    ->relationship('analyze', 'title') // Assuming `name` is the column in the analyzes table
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('customer.name_fa')
                ->label('نام مشتری')
                ->formatStateUsing(function ($state, $record) {
                    return $record->customer->name_fa . ' ' . $record->customer->family_fa;
                }),
                TextColumn::make('analyze_id')
                    ->label('عنوان آنالیز')
                    ->searchable()
                    ->sortable()
                    ->wrap()
                    ->getStateUsing(function ($record) {
                        return $record->analyze ? $record->analyze->title : null;  // Use the title from the related model
                    }),
                TextColumn::make('date')->label('تاریخ ثبت'),
                TextColumn::make('time')->label('زمان ثبت'),
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
