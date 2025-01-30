<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CustomerAnalysisResource\Pages;
use App\Filament\Resources\CustomerAnalysisResource\RelationManagers;
use App\Models\CustomerAnalysis;
use App\Models\Customers;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CustomerAnalysisResource extends Resource
{
    protected static ?string $model = CustomerAnalysis::class;


    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationLabel = 'مدیریت پذیرش آنالیز مشتریان';

    protected static ?string $navigationGroup = 'پذیرش';


    protected static ?string $pluralModelLabel = 'مدیریت پذیرش آنالیز مشتریان';

    protected static ?string $slug = 'customer-analysis';

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
                        1 => 'در انتظار',
                        2 => 'در حال پیشرفت',
                        3 => 'تکمیل شده',
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
            Tables\Columns\TextColumn::make('id')->label('کد'),
            Tables\Columns\TextColumn::make('customer.name_fa')
                ->label('نام مشتری')
                ->formatStateUsing(function ($state, $record) {
                    return $record->customer->name_fa . ' ' . $record->customer->family_fa;
                }),
            Tables\Columns\TextColumn::make('acceptance_date')
                ->label('تاریخ پذیرش'),
            Tables\Columns\TextColumn::make('analyze.title')
                ->label('آنالیز'),
            Tables\Columns\TextColumn::make('total_cost')
                ->label('هزینه کل'),
            Tables\Columns\TextColumn::make('status')
                ->label('وضعیت پذیرش')
                ->formatStateUsing(fn ($state) => match ($state) {
                    1 => 'در انتظار',
                    2 => 'در حال پیشرفت',
                    3 => 'تکمیل شده',
                    default => 'نامشخص',
                }),
            Tables\Columns\TextColumn::make('tracking_code')
                ->label('کد پیگیری'),
            Tables\Columns\TextColumn::make('created_at')
                ->label('تاریخ ایجاد')
                ->dateTime(),
        ])
        ->filters([
            Filter::make('status')
                ->label('وضعیت پذیرش')
                ->form([
                    Forms\Components\Select::make('status')
                        ->label('وضعیت')
                        ->options([
                            1 => 'در انتظار',
                            2 => 'در حال پیشرفت',
                            3 => 'تکمیل شده',
                        ]),
                ]),
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
            'index' => Pages\ListCustomerAnalyses::route('/'),
            'create' => Pages\CreateCustomerAnalysis::route('/create'),
            'edit' => Pages\EditCustomerAnalysis::route('/{record}/edit'),
        ];
    }
}
