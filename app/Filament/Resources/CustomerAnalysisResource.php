<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CustomerAnalysisResource\Pages;
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
use Illuminate\Support\Facades\App;
use Morilog\Jalali\Jalalian;

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
                    ->default($customerAnalysis->grant ?? 0)
                    ->afterStateUpdated(function ($state, $set, $get) {
                        // Get the total cost value
                        $total = $get('total_cost') ?? 0;
                        
                        // If grant is 0, set applicant_share to total and reset network_share and network_id
                        if ($state == 0) {
                            $set('applicant_share', $total);
                            $set('network_share', null);
                            $set('network_id', null);
                        } else if ($state == 1) {
                            // If grant is 1, calculate applicant_share based on network_share
                            $networkShare = $get('network_share') ?? 0;
                            $applicantShare = $total - $networkShare;
                            $set('applicant_share', $applicantShare);
                            $set('network_share', $networkShare);
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
                ->default(0)
                ->afterStateUpdated(function ($state, $set, $get) {
                    // Get current values of other relevant fields
                    $samplesNumber = $get('samples_number') ?? 1;
                    $analyzeId = $get('analyze_id') ?? 1;
                    $priceAnalysis = \App\Models\price_analysis::where('analyze_id', $analyzeId)->first();
                    
                    $base_cost = $priceAnalysis ? $priceAnalysis->price : 0;
                    $additional_cost = $get('additional_cost') ?? 0;
                    $value_added = $get('value_added') == 1 ? ($samplesNumber * $base_cost * 10) / 100 : 0;
                    
                    // Get discount-related values
                    $discountType = $state;
                    $discountNum = $get('discount_num') ?? 0;
                    
                    // Calculate total cost based on discount type
                    $totalCost = 0;
            
                    if ($discountType == 0) {  // No discount
                        $totalCost = ($samplesNumber * $base_cost) + $value_added;
                    } elseif ($discountType == 2) {  // Fixed discount (discount_num is amount)
                        $totalCost = (($samplesNumber * $base_cost) + $value_added) - $discountNum;
                    } elseif ($discountType == 1) {  // Percentage discount
                        $totalCost = (($samplesNumber * $base_cost) + $value_added) - (($samplesNumber * $base_cost + $value_added) * $discountNum) / 100;
                    }
            
                    // Update the total cost field
                    $set('total_cost', $totalCost);
                }),
            

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
                    ->default(now())  // Sets the default date to today's date
                    ->reactive()
                    ->afterStateUpdated(function ($state, $set) {
                        // Call the function to generate tracking code whenever the date changes
                        $trackingCode = self::generateTrackingCode($state);
                        $set('tracking_code', $trackingCode);  // Update the tracking_code field with the new tracking code
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
                    ->id('value_added')
                    ->reactive() // Makes it reactive
                    ->afterStateUpdated(function ($state, $set, $get) {
                        // Get current values of other relevant fields
                        $samplesNumber = $get('samples_number') ?? 1;
                        $analyzeId = $get('analyze_id') ?? 1;
                        $priceAnalysis = \App\Models\price_analysis::where('analyze_id', $analyzeId)->first();
                        
                        $base_cost = $priceAnalysis ? $priceAnalysis->price : 0;
                        $additional_cost = $get('additional_cost') ?? 0;
                
                        // Calculate value added based on 'value_added' toggle
                        if ($state == 1) {  // if value_added is enabled
                            $valueadded = ($samplesNumber * $base_cost * 10) / 100;
                        } else {  // if value_added is disabled
                            $valueadded = 0;
                        }
                
                        // Calculate the new total cost
                        $new_total_cost = ($samplesNumber * $base_cost) + $additional_cost + $valueadded;
                
                        // Update the total_cost field
                        $set('total_cost', $new_total_cost);
                    }),
                
                
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
                        ->reactive() // React when applicant_share changes
                        ->default(function ($get) {
                            // Set the default value of applicant_share to the current total_cost
                            return $get('total_cost') ?? 0;
                        })
                        ->afterStateUpdated(function ($state, $set, $get) {
                            // Get total cost and update network_share based on applicant_share
                            $total = $get('total_cost') ?? 0;
                            $networkShare = $total - $state; // Subtract applicant_share from total cost to get network_share
                            $set('network_share', $networkShare);
                        }),


                        Forms\Components\TextInput::make('network_share')
                        ->label('سهم شبکه')
                        ->required()
                        ->id('network_share')
                        ->visible(fn ($state, $get) => $get('grant') == 1)  // Only visible if grant == 1
                        ->reactive() // React when network_share changes
                        ->afterStateUpdated(function ($state, $set, $get) {
                            // Get total cost and update applicant_share based on network_share
                            $total = $get('total_cost') ?? 0;
                            $applicantShare = $total - $state; // Subtract network_share from total cost to get applicant_share
                            $set('applicant_share', $applicantShare);
                        }),
                    

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
                    ->nullable()
                    ->visible(fn ($state, $get) => $get('discount') == 1)  // Only visible if discount is set to 'درصد'
                    ->afterStateUpdated(function ($state, $set, $get) {
                        // Recalculate total cost whenever the discount_num changes
                        $samplesNumber = $get('samples_number') ?? 1;
                        $analyzeId = $get('analyze_id') ?? 1;
                        $priceAnalysis = \App\Models\price_analysis::where('analyze_id', $analyzeId)->first();
                        
                        $base_cost = $priceAnalysis ? $priceAnalysis->price : 0;
                        $additional_cost = $get('additional_cost') ?? 0;
                        $value_added = $get('value_added') == 1 ? ($samplesNumber * $base_cost * 10) / 100 : 0;
                
                        $discountType = $get('discount') ?? 0;
                        $discountNum = $state ?? 0;
                
                        // Calculate total cost based on discount type
                        $totalCost = 0;
                
                        if ($discountType == 0) {  // No discount
                            $totalCost = ($samplesNumber * $base_cost) + $value_added;
                        } elseif ($discountType == 1) {  // Fixed discount (discount_num is amount)
                            $totalCost = (($samplesNumber * $base_cost) + $value_added) - $discountNum;
                        } elseif ($discountType == 2) {  // Percentage discount
                            $totalCost = (($samplesNumber * $base_cost) + $value_added) - (($samplesNumber * $base_cost + $value_added) * $discountNum) / 100;
                        }
                
                        // Update the total cost field
                        $set('total_cost', $totalCost);
                    }),

                    Forms\Components\TextInput::make('discount_num')
                    ->label('مبلغ تخفیف')
                    ->numeric()
                    ->nullable()
                    ->visible(fn ($state, $get) => $get('discount') == 2)  // Only visible if discount is set to 'مبلغ'
                    ->afterStateUpdated(function ($state, $set, $get) {
                        // Recalculate total cost whenever the discount_num changes
                        $samplesNumber = $get('samples_number') ?? 1;
                        $analyzeId = $get('analyze_id') ?? 1;
                        $priceAnalysis = \App\Models\price_analysis::where('analyze_id', $analyzeId)->first();
                        
                        $base_cost = $priceAnalysis ? $priceAnalysis->price : 0;
                        $additional_cost = $get('additional_cost') ?? 0;
                        $value_added = $get('value_added') == 1 ? ($samplesNumber * $base_cost * 10) / 100 : 0;
                
                        $discountType = $get('discount') ?? 0;
                        $discountNum = $state ?? 0;
                
                        // Calculate total cost based on discount type
                        $totalCost = 0;
                
                        if ($discountType == 0) {  // No discount
                            $totalCost = ($samplesNumber * $base_cost) + $value_added;
                        } elseif ($discountType == 1) {  // Fixed discount (discount_num is amount)
                            $totalCost = (($samplesNumber * $base_cost) + $value_added) - $discountNum;
                        } elseif ($discountType == 2) {  // Percentage discount
                            $totalCost = (($samplesNumber * $base_cost) + $value_added) - (($samplesNumber * $base_cost + $value_added) * $discountNum) / 100;
                        }
                
                        // Update the total cost field
                        $set('total_cost', $totalCost);
                    }),

                // Forms\Components\FileUpload::make('scan_form')
                //     ->label('اسکن فرم')
                //     ->image()
                //     ->disk('public')
                //     ->directory('images')
                //     ->nullable(),


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
                    ->nullable()  // The tracking code will be populated based on the date
                    ->default(function ($get) {
                        // Set the default tracking code based on the current date
                        $acceptanceDate = now()->format('Y/m/d');  // Get current date (today)
                        return self::generateTrackingCode($acceptanceDate);  // Use the generateTrackingCode function
                    }),
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

    public static function beforeSave($record, array $data): array
{
    // Get the acceptance date from the data
    $acceptanceDate = $data['acceptance_date'] ?? null;

    // If the acceptance date is set, generate the tracking code
    if ($acceptanceDate) {
        // Call the function to generate the tracking code
        $trackingCode = self::generateTrackingCode($acceptanceDate);

        // Add the generated tracking code to the data
        $data['tracking_code'] = $trackingCode;
    }

    // Return the modified data
    return $data;
}

public static function generateTrackingCode($acceptanceDate)
{
    // Convert the acceptance date to the Persian (Jalali) calendar
    $persianDate = Jalalian::fromCarbon(\Carbon\Carbon::parse($acceptanceDate))->format('Ymd');

    // Look for the latest record with the same Persian date in the tracking code
    $lastTrackingCode = CustomerAnalysis::where('tracking_code', 'like', $persianDate . '%')
        ->orderBy('tracking_code', 'desc')
        ->first();

    if ($lastTrackingCode) {
        // If a previous record exists, increment the last 5 digits of the tracking code
        $lastNumber = substr($lastTrackingCode->tracking_code, -5);
        $nextNumber = (int)$lastNumber + 1;
        // Pad the incremented number to ensure it's always 5 digits
        $trackingCode = str_pad($nextNumber, 5, '0', STR_PAD_LEFT);
    } else {
        // If no previous record, start from "00001"
        $trackingCode = '00001';
    }

    // Combine the Persian date with the incremented tracking number
    $trackingCode = $persianDate . $trackingCode;

    return $trackingCode;
}
}
