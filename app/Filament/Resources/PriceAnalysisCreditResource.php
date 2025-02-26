<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PriceAnalysisCreditResource\Pages;
use App\Models\Analyze;
use App\Models\Customers;
use App\Models\price_analysis_credit;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Morilog\Jalali\Jalalian;
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
                        Customers::whereNotNull('name_fa')
                            ->whereNotNull('family_fa')
                            ->get()
                            ->mapWithKeys(function ($customer) {
                                return [$customer->id => $customer->name_fa . ' ' . $customer->family_fa];
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
                ->maxLength(10)
                ->required()
                ->default(now()->format('Y-m-d')) // Set the default to the current date
                ->hidden(), // Hide the field from the user
            TextInput::make('time')
                ->label('زمان ثبت')
                ->maxLength(10)
                ->required()
                ->default(now()->format('H:i:s')) // Set the default to the current time
                ->hidden(), 
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
            Tables\Columns\TextColumn::make('created_at')
                ->label(__('filament.labels.created_at'))
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
            'index' => Pages\ListPriceAnalysisCredits::route('/'),
            'create' => Pages\CreatePriceAnalysisCredit::route('/create'),
            'edit' => Pages\EditPriceAnalysisCredit::route('/{record}/edit'),
        ];
    }
}
