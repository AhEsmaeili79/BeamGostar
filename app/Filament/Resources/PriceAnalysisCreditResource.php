<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PriceAnalysisCreditResource\Pages;
use App\Filament\Resources\PriceAnalysisCreditResource\RelationManagers;
use App\Models\Analyze;
use App\Models\Customers;
use App\Models\price_analysis_credit;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PriceAnalysisCreditResource extends Resource
{
    protected static ?string $model = price_analysis_credit::class;

    
    protected static ?string $label = 'هزینه آنالیز مشتریان اعتباری';

    protected static ?string $navigationGroup = 'اطلاعات پایه';

    protected static ?string $navigationLabel = 'هزینه آنالیز مشتریان اعتباری';

    protected static ?string $pluralLabel = 'هزینه آنالیز مشتریان اعتباری';

    protected static ?string $singularLabel = 'هزینه ها';

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?int $navigationSort = 9;
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('customers_id')
                ->label('مشتری')
                ->options(
                    Customers::whereNotNull('name_fa')  // Ensure 'name_fa' is not null
                        ->whereNotNull('family_fa')    // Ensure 'family_fa' is not null
                        ->where('clearing_type', 1)    // Filter by clearing_type = 1
                        ->get()
                        ->mapWithKeys(function ($customer) {
                            return [$customer->id => $customer->name_fa . ' ' . $customer->family_fa];  // Concatenate name_fa and family_fa
                        })
                )
                ->required()
                ->searchable()
                ->columnSpan([
                    'default' => 2,  // 1 column span on larger screens
                    'sm' => 1,       // 2 column span on small screens (screens smaller than 600px)
                ]),


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
            Tables\Columns\TextColumn::make('customer.name_fa') // Access the 'name_fa' through the 'customer' relationship
                    ->label('Customer Name')
                    ->formatStateUsing(function ($state, $record) {
                        return $record->customer->name_fa . ' ' . $record->customer->family_fa; // Concatenate 'name_fa' and 'family_fa'
                    }),
    
            Tables\Columns\TextColumn::make('analyze.title')
                ->label('آنالیز')
                ->sortable()
                ->searchable(),
    
            Tables\Columns\TextColumn::make('price')  // Display price
                ->label('قیمت(ریال)')
                ->sortable(),
    
            Tables\Columns\TextColumn::make('date')  // Display date
                ->label('تاریخ ثبت')
                ->sortable()
                ->date(),
    
            Tables\Columns\TextColumn::make('time')  // Display time
                ->label('زمان ثبت')
                ->sortable()
                ->time(),
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
            'index' => Pages\ListPriceAnalysisCredits::route('/'),
            'create' => Pages\CreatePriceAnalysisCredit::route('/create'),
            'edit' => Pages\EditPriceAnalysisCredit::route('/{record}/edit'),
        ];
    }
}
