<?php

namespace App\Filament\Resources;

use App\Filament\Resources\FullCustomerAnalysisResource\Pages;
use App\Models\CustomerAnalysis;
use App\Models\Customers;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\App;

class FullCustomerAnalysisResource extends Resource
{
    protected static ?string $model = CustomerAnalysis::class;

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->where('status', 1);
    }

    protected static ?string $label = 'مدیریت آنالیز های کامل';

    protected static ?string $navigationGroup = 'پذیرش';

    protected static ?string $navigationLabel = 'مدیریت آنالیز های کامل';

    protected static ?string $pluralLabel = 'مدیریت آنالیز های کامل';

    protected static ?string $singularLabel = 'مدیریت آنالیز های کامل';
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?int $navigationSort = 3;
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                    Forms\Components\Select::make('customers_id')
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
                    Forms\Components\DatePicker::make('acceptance_date')
                        ->label('تاریخ پذیرش')
                        ->required(),
                    Forms\Components\Select::make('get_answers_id')
                        ->label('نحوه دریافت جواب آنالیز')
                        ->relationship('getAnswers', 'title')
                        ->required(),
                    Forms\Components\Select::make('analyze_id')
                        ->label('آنالیز')
                        ->relationship('analyze', 'title')
                        ->required(),
                    Forms\Components\TextInput::make('samples_number')
                        ->label('تعداد نمونه')
                        ->numeric()
                        ->required(),
                    Forms\Components\TextInput::make('analyze_time')
                        ->label('کل زمان آنالیز')
                        ->required(),
                    Forms\Components\Toggle::make('value_added')
                        ->label('ارزش افزوده'),
                    Forms\Components\Toggle::make('grant')
                        ->label('گرنت دارد'),
                    Forms\Components\TextInput::make('additional_cost')
                        ->label('هزینه اضافه'),
                    Forms\Components\TextInput::make('additional_cost_text')
                        ->label('توضیحات هزینه اضافه'),
                    Forms\Components\TextInput::make('total_cost')
                        ->label('هزینه کل')
                        ->required(),
                    Forms\Components\TextInput::make('applicant_share')
                        ->label('سهم متقاضی')
                        ->required(),
                    Forms\Components\TextInput::make('network_share')
                        ->label('سهم شبکه')
                        ->required(),
                    Forms\Components\TextInput::make('network_id')
                        ->label('ID شبکه')
                        ->required(),
                    Forms\Components\Select::make('payment_method_id')
                        ->label('نحوه پرداخت')
                        ->relationship('paymentMethod', 'title')
                        ->required(),
                    Forms\Components\Toggle::make('discount')
                        ->label('تخفیف'),
                    Forms\Components\TextInput::make('discount_num')
                        ->label('مبلغ / درصد تخفیف'),
                    Forms\Components\FileUpload::make('scan_form')
                        ->label('اسکن فرم')
                        ->directory('uploads/scan_forms') // Save files to a specific directory
                        ->required(),
                    Forms\Components\Textarea::make('description')
                        ->label('توضیحات پذیرش'),
                    Forms\Components\Toggle::make('priority')
                        ->label('اولویت'),
                    Forms\Components\Select::make('status')
                        ->label('وضعیت پذیرش')
                        ->options([
                            0 => 'منتظر پرداخت',
                            1 => 'پذیرش کامل',
                            2 => 'در انتظار',
                            3 => 'کنسل',
                            4 => 'تایید مالی',
                            5 => 'منتظر تایید مدیریت فنی',
                            8 => 'منتظر تایید مدیریت مالی',
                        ])
                        ->required(),
                    Forms\Components\TextInput::make('tracking_code')
                        ->label('کد پیگیری'),
                    Forms\Components\DatePicker::make('date_answer')
                        ->label('تاریخ جوابدهی'),
                    Forms\Components\DatePicker::make('upload_answer')
                        ->label('تاریخ بارگذاری جواب'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
             ->columns([
            Tables\Columns\TextColumn::make('id')->label('ردیف'),
            Tables\Columns\TextColumn::make('customer.name_fa')
                ->label('نام مشتری')
                ->formatStateUsing(function ($state, $record) {
                    return $record->customer->name_fa . ' ' . $record->customer->family_fa;
                }),
            Tables\Columns\TextColumn::make('analyze.title')
                ->label('آنالیز'),
            Tables\Columns\TextColumn::make('total_cost')
                ->label('هزینه کل'),
            Tables\Columns\TextColumn::make('status')
                ->label('وضعیت پذیرش')
                ->formatStateUsing(fn ($state) => match ($state) {
                    0 => 'منتظر پرداخت',
                    1 => 'پذیرش کامل',
                    2 => 'در انتظار',
                    3 => 'کنسل',
                    4 => 'تایید مالی',
                    5 => 'منتظر تایید مدیریت فنی',
                    8 => 'منتظر تایید مدیریت مالی',
                    default => 'نامشخص',
                }),
            Tables\Columns\TextColumn::make('tracking_code')
                ->label('کد پیگیری'),
            Tables\Columns\TextColumn::make('acceptance_date')
                ->label('تاریخ پذیرش')
                ->dateTime()
                ->unless(App::isLocale('en'), fn (Tables\Columns\TextColumn $column) => $column->jalaliDate()),
        ])
            ->filters([
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
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
            // Add relations if needed
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListFullCustomerAnalyses::route('/'),
            'edit' => Pages\EditFullCustomerAnalysis::route('/{record}/edit'),
        ];
    }
}
