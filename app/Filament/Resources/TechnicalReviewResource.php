<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TechnicalReviewResource\Pages;
use App\Filament\Resources\TechnicalReviewResource\RelationManagers;
use App\Models\Analyze;
use App\Models\CustomerAnalysis;
use App\Models\TechnicalReview;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class TechnicalReviewResource extends Resource
{
    protected static ?string $model = TechnicalReview::class;

    protected static ?string $label = 'تایید فنی ';

    protected static ?string $navigationGroup = 'مدیریت فنی';

    protected static ?string $navigationLabel = 'مدیریت فنی آنالیز مشتریان';

    protected static ?string $pluralLabel = 'مدیریت فنی آنالیز مشتریان';

    protected static ?string $singularLabel = 'مدیریت فنی آنالیز مشتریان';

    protected static ?string $navigationIcon = 'heroicon-o-wrench';

    

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('customer_analysis_id')
                ->options(function () {
                    return CustomerAnalysis::query()
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

                // State Field (Tiny Integer)
                Select::make('state')
                    ->label('وضعیت')
                    ->options([
                        0 => 'عدم تایید',
                        1 => 'تایید',
                    ])
                    ->required(),

                // Text Field (Textarea)
                Textarea::make('text')
                    ->label('توضیحات درصورت عدم تایید')
                    ->nullable(),
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
                TextColumn::make('id')
                    ->label('ID')
                    ->sortable()
                    ->searchable(),

                // Customer Analysis Column (Foreign Key)
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

                // State Column
                BadgeColumn::make('state')
                    ->label('وضعیت')
                    ->getStateUsing(function ($record) {
                        return $record->state == 1 ? 'تایید' : 'عدم تایید';
                    })
                    ->color(function ($state) {
                        return $state == 'تایید' ? 'success' : 'danger';
                    }),
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
            'index' => Pages\ListTechnicalReviews::route('/'),
            'create' => Pages\CreateTechnicalReview::route('/create'),
            'edit' => Pages\EditTechnicalReview::route('/{record}/edit'),
        ];
    }
}
