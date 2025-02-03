<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CustomerAnalysisResource\Pages;
use App\Models\CustomerAnalysis;
use App\Models\Customers;
use App\Models\Analyze;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Table;
use Illuminate\Support\Facades\App;

class CustomerAnalysisResource extends Resource
{
    protected static ?string $model = CustomerAnalysis::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationLabel = 'مدیریت آنالیز مشتریان';

    protected static ?string $navigationGroup = 'پذیرش';

    protected static ?string $pluralModelLabel = 'مدیریت آنالیز مشتریان';

    protected static ?string $label = 'آنالیز مشتریان';

    protected static ?string $pluralLabel = 'مدیریت آنالیز مشتریان';

    protected static ?string $singularLabel = 'آنالیزگر';

    protected static ?string $slug = 'customer-analysis';
    
    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {

        $customerAnalysis = new CustomerAnalysis(); 

        return $form
            ->schema([

                Forms\Components\Radio::make('grant')
                        ->options([
                            0 => 'ندارد',
                            1 => 'دارد',
                        ])
                        ->label('گرنت')
                        ->inline()
                        ->required()
                        ->reactive()
                        ->default($customerAnalysis->grant ?? 0),

                Forms\Components\Radio::make('discount')
                        ->options([
                            0 => 'ندارد',
                            1 => 'درصد',
                            2 => 'مبلغ',
                        ])
                        ->label('تخفیف')
                        ->inline()
                        ->required()
                        ->reactive()
                        ->default($customerAnalysis->discount ?? 0),

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
                    ->required()
                    ->default(now()),

                Forms\Components\Select::make('get_answers_id')
                    ->label('نحوه دریافت جواب آنالیز')
                    ->relationship('getAnswers', 'title')
                    ->required(),
                    
                Forms\Components\Select::make('analyze_id')
                    ->label('آنالیز')
                    ->options(Analyze::all()->pluck('title', 'id')) 
                    ->default(1)
                    ->required()
                    ->searchable()
                    ->reactive() 
                    ->afterStateUpdated(function ($state, $set, $get) {
                        $samplesNumber = $get('samples_number') ?? 1;
                        $priceAnalysis = \App\Models\price_analysis::where('analyze_id', $state)->first();
                        
                        $additional_cost = $get('additional_cost') ?? 0;
                        
                        if ($priceAnalysis) {
                            $new_total_cost = ($samplesNumber * $priceAnalysis->price) + $additional_cost;
                            $set('total_cost', $new_total_cost);
                        }
                    }),
                
                Forms\Components\TextInput::make('samples_number')
                ->label('تعداد نمونه')
                ->numeric()
                ->required()
                ->suffix('عدد')
                ->default(1)
                ->reactive() 
                ->afterStateUpdated(function ($state, $set, $get) {
                    $analyzeId = $get('analyze_id') ?? 1;
                    $priceAnalysis = \App\Models\price_analysis::where('analyze_id', $analyzeId)->first();
                    
                    $additional_cost = $get('additional_cost') ?? 0;
                    
                    if ($priceAnalysis) {
                        $new_total_cost = ($state * $priceAnalysis->price) + $additional_cost;
                        $set('total_cost', $new_total_cost);
                    }
                }),

                Forms\Components\TextInput::make('analyze_time')
                    ->label('کل زمان آنالیز')
                    ->numeric()
                    ->default($customerAnalysis->analyze_time ?? 0)
                    ->id('analyze_time'),

                Forms\Components\Toggle::make('priority')
                    ->label('اولویت'),

                Forms\Components\Toggle::make('value_added')
                    ->label('ارزش افزوده')
                    ->id('value_added'),
                
                    Forms\Components\TextInput::make('additional_cost')
                    ->label('هزینه اضافه')
                    ->numeric()
                    ->suffix('ریال')
                    ->id('additional_cost')
                    ->default($customerAnalysis->additional_cost ?? 0)
                    ->reactive() 
                    ->afterStateUpdated(function ($state, $set, $get) {
                        $samplesNumber = $get('samples_number') ?? 1;
                        $analyzeId = $get('analyze_id') ?? 1;
                        $priceAnalysis = \App\Models\price_analysis::where('analyze_id', $analyzeId)->first();
                        $base_cost = $priceAnalysis ? $priceAnalysis->price : 0;
                
                        $new_total_cost = ($samplesNumber * $base_cost) + $state;
                
                        $set('total_cost', $new_total_cost);
                    }),
                

                    Forms\Components\TextInput::make('total_cost')
                        ->label('هزینه کل')
                        ->nullable()
                        ->required() 
                        ->suffix('ریال')
                        ->extraAttributes(['id' => 'total_cost'])
                        ->default(function ($get) {
                            $priceAnalysis = \App\Models\price_analysis::where('analyze_id', 1)->first();
                            return $priceAnalysis ? $priceAnalysis->price : 0;
                        })
                        ->reactive() 
                        ->afterStateUpdated(function ($state, $set, $get) {
                        }),


                    Forms\Components\TextInput::make('applicant_share')
                    ->label('سهم متقاضی')
                    ->required()
                    ->numeric()
                    ->default(0)
                    ->extraAttributes([
                        'x-bind:value' => 'grant == 0 ? total_cost : applicant_share',
                    ]),

                Forms\Components\TextInput::make('network_share')
                    ->label('سهم شبکه')
                    ->required()
                    ->id('network_share')
                    ->visible(fn ($state, $get) => $get('grant') == 1),

                Forms\Components\TextInput::make('network_id')
                    ->label('ID شبکه')
                    ->required()
                    ->default(null)
                    ->visible(fn ($state, $get) => $get('grant') == 1),

                Forms\Components\Select::make('payment_method_id')
                    ->label('نحوه پرداخت')
                    ->relationship('paymentMethod', 'title')
                    ->required(),
                    
                Forms\Components\TextInput::make('discount_num')
                    ->label('درصد تخفیف')
                    ->numeric()
                    ->id('discount_num')
                    ->nullable()
                    ->visible(fn ($state, $get) => $get('discount') == 1),

                    Forms\Components\TextInput::make('discount_num')
                    ->label('مبلغ تخفیف')
                    ->numeric()
                    ->nullable()
                    ->visible(fn ($state, $get) => $get('discount') == 2),

                Forms\Components\FileUpload::make('scan_form')
                    ->label('اسکن فرم')
                    ->image()
                    ->disk('public')
                    ->directory('images')
                    ->nullable(),


                Forms\Components\Textarea::make('description')
                    ->label('توضیحات پذیرش'),

                Forms\Components\Select::make('status')
                    ->label('وضعیت پذیرش')
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
                    ])
                    ->required(),

                Forms\Components\TextInput::make('tracking_code')
                    ->label('کد پیگیری')
                    ->nullable(),

                Forms\Components\DatePicker::make('date_answer')
                    ->label('تاریخ جوابدهی')
                    ->jalali(),

                Forms\Components\DatePicker::make('upload_answer')
                    ->label('تاریخ بارگذاری جواب')
                    ->jalali(),
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
                Tables\Columns\TextColumn::make('acceptance_date')
                    ->label('تاریخ پذیرش'),
                Tables\Columns\TextColumn::make('date_answer')
                    ->label('تاریخ جوابدهی')
                    ->dateTime()
                    ->unless(App::isLocale('en'), fn (Tables\Columns\TextColumn $column) => $column->jalaliDate()),
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
                        6 => 'تایید مدیریت فنی',
                        7 => 'آنالیز تکمیل',
                        8 => 'منتظر تایید مدیریت مالی',
                        default => 'نامشخص',
                    }),
                Tables\Columns\TextColumn::make('tracking_code')
                    ->label('کد پیگیری'),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('تاریخ ایجاد')
                    ->dateTime()
                    ->unless(App::isLocale('en'), fn (Tables\Columns\TextColumn $column) => $column->jalaliDateTime()),
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
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
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
