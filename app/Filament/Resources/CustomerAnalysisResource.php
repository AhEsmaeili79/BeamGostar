<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CustomerAnalysisResource\Pages;
use App\Http\Controllers\CustomerAnalysisController;
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
                    ->default(0)
                    ->afterStateUpdated(function ($state, $set, $get) {
                        $totalCost = $get('total_cost');
                        if ($state == 0) {
                            $set('applicant_share', $totalCost);
                            $set('network_share', 0); 
                        } elseif ($state == 1) {
                            $networkShare = $get('network_share');
                            $set('applicant_share', $totalCost - $networkShare);
                        }
                    }),
                    

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
                    ->default(0), 

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
                    ->jalali()
                    ->default(now())
                    ->reactive()
                    ->afterStateUpdated(function ($state, $set) {
                        // Call the function to generate tracking code from the controller
                        $trackingCode = (new CustomerAnalysisController())->generateTrackingCode($state);
                        $set('tracking_code', $trackingCode);  
                    }),
                        

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
                    ->reactive(),
                
                Forms\Components\TextInput::make('samples_number')
                    ->label('تعداد نمونه')
                    ->numeric()
                    ->required()
                    ->suffix('عدد')
                    ->default(1)
                    ->reactive(),

                Forms\Components\TextInput::make('analyze_time')
                    ->label('کل زمان آنالیز')
                    ->numeric()
                    ->default($customerAnalysis->analyze_time ?? 0)
                    ->id('analyze_time'),

                Forms\Components\Toggle::make('priority')
                    ->label('اولویت'),

                Forms\Components\Toggle::make('value_added')
                    ->label('ارزش افزوده')
                    ->id('value_added')
                    ->reactive(),
                
                Forms\Components\TextInput::make('additional_cost')
                    ->label('هزینه اضافه')
                    ->numeric()
                    ->suffix('ریال')
                    ->id('additional_cost')
                    ->default(0)
                    ->reactive()
                    ->afterStateUpdated(function ($state, $set, $get) {
                        $totalCost = $get('total_cost');
                        $set('total_cost', $state + $totalCost);
                        if ($get('grant') == 0) {
                            $set('applicant_share', $state + $totalCost);
                        }
                    }),
                    Forms\Components\TextInput::make('base_total_cost')
                        ->label('هزینه کل اولیه')
                        ->numeric()
                        ->default(0)
                        ->hidden(),
                
                    Forms\Components\TextInput::make('total_cost')
                        ->label('هزینه کل بعد از تخفیف')
                        ->nullable()
                        ->required()
                        ->suffix('ریال')
                        ->reactive()
                        ->readonly()
                        ->extraAttributes(['class' => 'bg-gray-300'])
                        ->afterStateUpdated(function ($state, $set, $get) {
                            $grant = $get('grant');
                            if ($grant == 0) {
                                $set('applicant_share', $state);
                            } elseif ($grant == 1) {
                                $networkShare = $get('network_share') ?? 0;
                                $set('applicant_share', max($state - $networkShare, 0)); 
                            }
                        }),

                Forms\Components\TextInput::make('applicant_share')
                    ->label('سهم متقاضی')
                    ->required()
                    ->numeric()
                    ->reactive()
                    ->default(0)
                    ->readonly(),


                Forms\Components\TextInput::make('network_share')
                    ->label('سهم شبکه')
                    ->required()
                    ->numeric()
                    ->visible(fn ($state, $get) => $get('grant') == 1) 
                    ->reactive()
                    ->default(0)
                    ->afterStateUpdated(function ($state, $set, $get) {
                        $totalCost = $get('total_cost');
                        $grant = $get('grant');
                        if ($grant == 1) {
                            $set('applicant_share', $totalCost - $state);
                        }
                    }),

                Forms\Components\TextInput::make('network_id')
                    ->label('ID شبکه')
                    ->required()
                    ->default(null)
                    ->visible(fn ($state, $get) => $get('grant') == 1),

                Forms\Components\Select::make('payment_method_id') 
                    ->label('نحوه پرداخت')
                    ->relationship('paymentMethod', 'title')
                    ->required()
                    ->afterStateUpdated(function ($state, callable $set) {
                        $status = ($state == 1) ? 1 : 0;
                        
                        $set('status', $status);
                    }),
                    
                Forms\Components\TextInput::make('discount_num')
                    ->label('درصد تخفیف')
                    ->numeric()
                    ->nullable()
                    ->visible(fn ($state, $get) => $get('discount') == 1)  
                    ->reactive(),

                Forms\Components\TextInput::make('discount_num')
                    ->label('مبلغ تخفیف')
                    ->numeric()
                    ->nullable()
                    ->visible(fn ($state, $get) => $get('discount') == 2) 
                    ->reactive() ,

                Forms\Components\Textarea::make('description')
                    ->label('توضیحات پذیرش'),

                Forms\Components\Hidden::make('status')
                    ->required(),

                Forms\Components\Hidden::make('tracking_code')
                    ->default(fn($get) => (new CustomerAnalysisController)->generateTrackingCode($get('acceptance_date')))
            ]
        );
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
                    ->label('تاریخ پذیرش')
                    ->dateTime()
                    ->unless(App::isLocale('en'), fn (Tables\Columns\TextColumn $column) => $column->jalaliDate()),
                
                Tables\Columns\TextColumn::make('date_answer')
                    ->label('تاریخ جوابدهی')
                    ->dateTime()
                    ->unless(App::isLocale('en'), fn (Tables\Columns\TextColumn $column) => $column->jalaliDate()),
                
                Tables\Columns\TextColumn::make('analyze.title')
                    ->label('آنالیز'),

                Tables\Columns\TextColumn::make('total_cost')
                    ->label('هزینه کل'),

                Tables\Columns\TextColumn::make('applicant_share')
                    ->label('سهم متقاضی'),

                Tables\Columns\TextColumn::make('status')
                    ->label('وضعیت پذیرش')
                    ->formatStateUsing(fn ($state) => CustomerAnalysisController::getStatusLabel($state)),
                    
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
                            ->options(CustomerAnalysisController::statusOptions()),
                    ]),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
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
        ];
    }

    public static function beforeSave($record, array $data): array
    {
        return (new CustomerAnalysisController)->beforeSave(request());
    }
    public static function boot(): void
    {
        parent::boot();

        // Add the JS file to the page
        \Filament\Facades\Filament::addScript(asset('js/custom.js'));
    }
}
