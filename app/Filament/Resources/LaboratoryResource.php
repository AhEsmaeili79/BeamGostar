<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LaboratoryResource\Pages;
use App\Filament\Resources\LaboratoryResource\RelationManagers;
use App\Models\Laboratory;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Ysfkaya\FilamentPhoneInput\Tables\PhoneColumn;
use Ysfkaya\FilamentPhoneInput\PhoneInputNumberType;
use Illuminate\Support\Facades\App;

class LaboratoryResource extends Resource
{
    protected static ?string $model = Laboratory::class;
    protected static ?string $label = 'آزمایشگاه';
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $pluralLabel = 'اپراتور آزمایشگاه ';
    protected static ?string $navigationGroup = 'آزمایشگاه ';
    protected static ?string $navigationLabel = 'اپراتور آزمایشگاه ';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('full_name_fa')
                    ->label('نام کامل فارسی')
                    ->maxLength(81),
                Forms\Components\TextInput::make('full_name_en')
                    ->label('نام کامل انگلیسی')
                    ->maxLength(81),
                Forms\Components\TextInput::make('national_code')
                    ->label('کد ملی')
                    ->required()
                    ->maxLength(6),
                Forms\Components\TextInput::make('national')
                    ->label('ملیت')
                    ->required()
                    ->maxLength(6),
                Forms\Components\TextInput::make('mobile')
                    ->label('موبایل')
                    ->required()
                    ->maxLength(11),
                Forms\Components\TextInput::make('customer_type')
                    ->label('نوع مشتری')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('clearing_type')
                    ->label('نوع تسویه')
                    ->required()
                    ->numeric(),
                Forms\Components\DatePicker::make('acceptance_date')
                    ->label('تاریخ پذیرش')
                    ->required(),
                Forms\Components\TextInput::make('samples_number')
                    ->label('تعداد نمونه')
                    ->required()
                    ->numeric()
                    ->default(0),
                Forms\Components\TextInput::make('analyze_id')
                    ->label('شناسه آنالیز')
                    ->numeric()
                    ->default(0),
                Forms\Components\TextInput::make('tracking_code')
                    ->label('کد پیگیری')
                    ->maxLength(20),
                Forms\Components\Textarea::make('scan_form')
                    ->label('فرم اسکن')
                    ->columnSpanFull(),
                Forms\Components\Textarea::make('description')
                    ->label('توضیحات')
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('priority')
                    ->label('اولویت')
                    ->numeric(),
                Forms\Components\TextInput::make('status')
                    ->label('وضعیت')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('state')
                    ->label('وضعیت داخلی')
                    ->numeric(),
                Forms\Components\TextInput::make('date_success')
                    ->label('تاریخ موفقیت')
                    ->maxLength(16),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('شناسه')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('full_name_fa')
                    ->label('نام کامل فارسی')
                    ->searchable(),
                Tables\Columns\TextColumn::make('full_name_en')
                    ->label('نام کامل انگلیسی')
                    ->searchable(),
                Tables\Columns\TextColumn::make('national')
                    ->label('ملیت')
                    ->searchable(),
                Tables\Columns\TextColumn::make('national_code')
                    ->label('کد ملی')
                    ->searchable(),
                    PhoneColumn::make('mobile')
                    ->displayFormat(PhoneInputNumberType::NATIONAL)
                    ->label('موبایل')
                    ->searchable(),
                Tables\Columns\TextColumn::make('customer_type')
                    ->label('نوع مشتری')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('clearing_type')
                    ->label('نوع تسویه')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('acceptance_date')
                    ->label('تاریخ پذیرش')
                    ->dateTime()
                    ->unless(App::isLocale('en'), fn (Tables\Columns\TextColumn $column) => $column->jalaliDate())
                    ->sortable(),
                Tables\Columns\TextColumn::make('samples_number')
                    ->label('تعداد نمونه')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('analyze_id')
                    ->label('شناسه آنالیز')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('tracking_code')
                    ->label('کد پیگیری')
                    ->searchable(),
                Tables\Columns\TextColumn::make('priority')
                    ->label('اولویت')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->label('وضعیت')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('state')
                    ->label('وضعیت داخلی')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('date_success')
                    ->label('تاریخ ثبت')
                    ->dateTime()
                    ->unless(App::isLocale('en'), fn (Tables\Columns\TextColumn $column) => $column->jalaliDate())
                    ->searchable(),
            ])
            ->filters([ 
                // Add any filters you need here
            ])
            ->actions([
                Tables\Actions\ViewAction::make()->label('مشاهده'),
            ])
            ->bulkActions([]); // No bulk actions for now
    }

    public static function getRelations(): array
    {
        return [
            // Add relations if needed
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListLaboratories::route('/'),
        ];
    }
}
