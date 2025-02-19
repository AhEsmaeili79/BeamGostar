<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MyAnalysesResource\Pages;
use App\Models\CustomerAnalysis;
use App\Models\Customers;
use App\Models\Analyze;
use Filament\Forms;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Support\Facades\App;
use Morilog\Jalali\Jalalian;
use Carbon\Carbon;

class MyAnalysesResource extends Resource
{
    protected static ?string $model = CustomerAnalysis::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationLabel = 'مدیریت آنالیز های من';

    protected static ?string $navigationGroup = 'مشتریان';

    protected static ?string $pluralModelLabel = 'مدیریت آنالیز های من';

    protected static ?string $label = 'آنالیز من';

    protected static ?string $pluralLabel = 'مدیریت آنالیز های من';

    protected static ?string $singularLabel =  'آنالیز من';

    protected static ?string $slug = 'my-analysis';
    
    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('id')->label('ردیف')->disabled(),
                Forms\Components\TextInput::make('customer.name_fa')
                    ->label('نام مشتری')
                    ->disabled()
                    ->formatStateUsing(fn ($state, $record) => $record->customer->name_fa . ' ' . $record->customer->family_fa),
                    Forms\Components\TextInput::make('acceptance_date')
                    ->label('تاریخ پذیرش')
                    ->disabled()
                    ->formatStateUsing(fn ($state) => Jalalian::fromCarbon(Carbon::parse($state))->format('Y/m/d')),
                Forms\Components\TextInput::make('analyze.title')
                    ->label('آنالیز')
                    ->disabled(),
                Forms\Components\TextInput::make('total_cost')
                    ->label('هزینه کل')
                    ->disabled(),
                Forms\Components\TextInput::make('tracking_code')
                    ->label('کد پیگیری')
                    ->disabled(),
                Forms\Components\TextInput::make('status')
                    ->label('وضعیت پذیرش')
                    ->disabled()
                    ->formatStateUsing(fn ($state) => match ($state) {
                        0 => 'منتظر پرداخت',
                        1 => 'پذیرش کامل',
                        2 => 'در انتظار',
                        3 => 'کنسل',
                        4 => 'تایید مالی',
                        5 => 'منتظر تایید مدیریت فنی',
                        6 => 'تایید مدیریت فنی',
                        7 => 'آنالیز تکمیل',
                        8 => 'منتظر تایید مدیریت مالی',
                        default => 'نامشخص',
                    }),
                Forms\Components\TextInput::make('samples_number')
                    ->label('تعداد نمونه')
                    ->disabled(),
                Forms\Components\TextInput::make('analyze_time')
                    ->label('کل زمان آنالیز')
                    ->disabled(),
                Forms\Components\TextInput::make('applicant_share')
                    ->label('سهم متقاضی')
                    ->disabled(),
                Forms\Components\TextInput::make('network_share')
                    ->label('سهم شبکه')
                    ->disabled(),
                Forms\Components\TextInput::make('network_id')
                    ->label('ID شبکه')
                    ->disabled(),
                Forms\Components\TextInput::make('payment_method_id')
                    ->label('نحوه پرداخت')
                    ->disabled(),
                Forms\Components\TextInput::make('description')
                    ->label('توضیحات پذیرش')
                    ->disabled(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')->label('ردیف'),
                TextColumn::make('customer.name_fa')
                    ->label('نام مشتری')
                    ->formatStateUsing(fn ($state, $record) => $record->customer->name_fa . ' ' . $record->customer->family_fa),
                TextColumn::make('acceptance_date')
                    ->label('تاریخ پذیرش')
                    ->dateTime()
                    ->unless(App::isLocale('en'), fn (TextColumn $column) => $column->jalaliDate()),
                TextColumn::make('analyze.title')
                    ->label('آنالیز'),
                TextColumn::make('total_cost')
                    ->label('هزینه کل'),
                TextColumn::make('status')
                    ->label('وضعیت پذیرش')
                    ->formatStateUsing(fn ($state) => match ($state) {
                        0 => 'منتظر پرداخت',
                        1 => 'پذیرش کامل',
                        2 => 'در انتظار',
                        3 => 'کنسل',
                        4 => 'تایید مالی',
                        5 => 'منتظر تایید مدیریت فنی',
                        6 => 'تایید مدیریت فنی',
                        7 => 'آنالیز تکمیل',
                        8 => 'منتظر تایید مدیریت مالی',
                        default => 'نامشخص',
                    }),
                TextColumn::make('tracking_code')
                    ->label('کد پیگیری'),
            ])
            ->filters([
                Filter::make('status')
                    ->label('وضعیت پذیرش')
                    ->form([
                        Forms\Components\Select::make('status')
                            ->label('وضعیت')
                            ->options([
                                0 => 'منتظر پرداخت',
                                1 => 'پذیرش کامل',
                                2 => 'در انتظار',
                                3 => 'کنسل',
                                4 => 'تایید مالی',
                                5 => 'منتظر تایید مدیریت فنی',
                                6 => 'تایید مدیریت فنی',
                                7 => 'آنالیز تکمیل',
                                8 => 'منتظر تایید مدیریت مالی',
                            ]),
                    ]),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
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
            'index' => Pages\ListMyAnalyses::route('/'),
        ];
    }
}










