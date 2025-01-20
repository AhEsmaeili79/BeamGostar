<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SampleResource\Pages;
use App\Filament\Resources\SampleResource\RelationManagers;
use App\Models\Sample;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SampleResource extends Resource
{
    protected static ?string $model = Sample::class;

    protected static ?string $navigationIcon = '';

    protected static ?string $navigationGroup = 'نمونه ها';

    protected static ?string $navigationLabel = 'نمونه ها';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('customer_analysis_id')
                ->options(function () {
                    return \App\Models\CustomerAnalysis::query()
                        ->join('customers', 'customers.id', '=', 'customer_analysis.customers_id')  // اتصال به جدول مشتریان
                        ->join('analyze', 'analyze.id', '=', 'customer_analysis.analyze_id')  // اتصال به جدول آنالیز
                        ->selectRaw('CONCAT(customers.name_fa, " ", customers.family_fa) AS full_name, analyze.title AS analyze_title, customer_analysis.id')
                        ->get()
                        ->mapWithKeys(function ($item) {
                            // ترکیب نام کامل مشتری و عنوان آنالیز
                            return [$item->id => $item->full_name . ' - ' . $item->analyze_title];
                        });
                })
                ->required()
                ->label('آنالیز مشتریان'),
                
                Forms\Components\Select::make('analyze_id')
                    ->label('آنالیز')
                    ->relationship('analyze', 'title') // Adjust the relationship if necessary
                    ->required(),
                
                Forms\Components\TextInput::make('sample_code')
                    ->label('کد پیگیری نمونه')
                    ->numeric()
                    ->required()
                    ->default('0000')
                    ->unique(),
                
                Forms\Components\TextInput::make('order')
                    ->label('اولویت')
                    ->numeric()
                    ->nullable(),
                
                Forms\Components\Select::make('status')
                    ->label('وضعیت نمونه')
                    ->options([
                        0 => 'غیرفعال',
                        1 => 'فعال',
                    ])
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('customerAnalysis')
                    ->label('آنالیز مشتریان')
                    ->getStateUsing(function ($record) {
                        return $record->customerAnalysis->customer->name_fa . ' ' . $record->customerAnalysis->customer->family_fa . ' - ' . $record->customerAnalysis->analyze->title;
                    }),
                
                Tables\Columns\TextColumn::make('analyze_id')
                    ->label('عنوان آنالیز')
                    ->searchable()
                    ->sortable()
                    ->wrap()
                    ->getStateUsing(function ($record) {
                        return $record->analyze ? $record->analyze->title : null;  // Use the title from the related model
                    }),
                
                Tables\Columns\TextColumn::make('sample_code')
                    ->label('کد پیگیری نمونه')
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('order')
                    ->label('اولویت')
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('status')
                    ->label('وضعیت نمونه')
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
            'index' => Pages\ListSamples::route('/'),
            'create' => Pages\CreateSample::route('/create'),
            'edit' => Pages\EditSample::route('/{record}/edit'),
        ];
    }
}
