<?php

namespace App\Filament\Resources;

use App\Filament\Resources\FullCustomerAnalysisResource\Pages;
use App\Filament\Resources\FullCustomerAnalysisResource\RelationManagers;
use App\Models\CustomerAnalysis;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class FullCustomerAnalysisResource extends Resource
{
    protected static ?string $model = CustomerAnalysis::class;

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->where('status', 7);
    }

    protected static ?string $label = 'مدیریت پذیرش آنالیز کامل مشتریان';

    protected static ?string $navigationGroup = 'پذیرش';

    protected static ?string $navigationLabel = 'مدیریت پذیرش آنالیز کامل';

    protected static ?string $pluralLabel = 'مدیریت پذیرش آنالیز کامل مشتریان';

    protected static ?string $singularLabel = 'مدیریت پذیرش آنالیز کامل مشتریان';
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('customers_id')->label('مشتریان')->required(),
                Forms\Components\DatePicker::make('acceptance_date')->label('تاریخ پذیرش'),
                Forms\Components\TextInput::make('get_answers_id')->label('نحوه دریافت جواب آنالیز'),
                Forms\Components\TextInput::make('analyze_id')->label('آنالیز'),
                Forms\Components\TextInput::make('samples_number')->label('تعداد نمونه'),
                Forms\Components\TextInput::make('analyze_time')->label('کل زمان آنالیز'),
                Forms\Components\TextInput::make('value_added')->label('ارزش افزوده'),
                Forms\Components\TextInput::make('grant')->label('گرنت دارد'),
                Forms\Components\TextInput::make('additional_cost')->label('هزینه اضافه'),
                Forms\Components\Textarea::make('additional_cost_text')->label('توضیحات هزینه اضافه'),
                Forms\Components\TextInput::make('total_cost')->label('هزینه کل'),
                Forms\Components\TextInput::make('applicant_share')->label('سهم متقاضی'),
                Forms\Components\TextInput::make('network_share')->label('سهم شبکه'),
                Forms\Components\TextInput::make('network_id')->label('ID شبکه'),
                Forms\Components\TextInput::make('payment_method_id')->label('نحوه پرداخت'),
                Forms\Components\TextInput::make('discount')->label('تخفیف'),
                Forms\Components\TextInput::make('discount_num')->label('مبلغ / درصد تخفیف'),
                Forms\Components\Textarea::make('description')->label('توضیحات پذیرش'),
                Forms\Components\TextInput::make('priority')->label('اولویت'),
                Forms\Components\TextInput::make('status')->label('وضعیت پذیرش')->required(),
                Forms\Components\TextInput::make('tracking_code')->label('کد پیگیری'),
                Forms\Components\DatePicker::make('date_answer')->label('تاریخ حدودی جوابدهی'),
                Forms\Components\FileUpload::make('upload_answer')->label('وضعیت پذیرش'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('customers_id')->label('مشتریان'),
                Tables\Columns\TextColumn::make('acceptance_date')->date()->label('تاریخ پذیرش'),
                Tables\Columns\TextColumn::make('get_answers_id')->label('نحوه دریافت جواب آنالیز'),
                Tables\Columns\TextColumn::make('analyze_id')->label('آنالیز'),
                Tables\Columns\TextColumn::make('samples_number')->label('تعداد نمونه'),
                Tables\Columns\TextColumn::make('analyze_time')->label('کل زمان آنالیز'),
                Tables\Columns\TextColumn::make('value_added')->label('ارزش افزوده'),
                Tables\Columns\TextColumn::make('grant')->label('گرنت دارد'),
                Tables\Columns\TextColumn::make('additional_cost')->label('هزینه اضافه'),
                Tables\Columns\TextColumn::make('additional_cost_text')->limit(50)->label('توضیحات هزینه اضافه'),
                Tables\Columns\TextColumn::make('total_cost')->label('هزینه کل'),
                Tables\Columns\TextColumn::make('applicant_share')->label('سهم متقاضی'),
                Tables\Columns\TextColumn::make('network_share')->label('سهم شبکه'),
                Tables\Columns\TextColumn::make('network_id')->label('ID شبکه'),
                Tables\Columns\TextColumn::make('payment_method_id')->label('نحوه پرداخت'),
                Tables\Columns\TextColumn::make('discount')->label('تخفیف'),
                Tables\Columns\TextColumn::make('discount_num')->label('مبلغ / درصد تخفیف'),
                Tables\Columns\TextColumn::make('description')->limit(50)->label('توضیحات پذیرش'),
                Tables\Columns\TextColumn::make('priority')->label('اولویت'),
                Tables\Columns\TextColumn::make('status')->label('وضعیت پذیرش'),
                Tables\Columns\TextColumn::make('tracking_code')->label('کد پیگیری'),
                Tables\Columns\TextColumn::make('date_answer')->date()->label('تاریخ حدودی جوابدهی'),
                Tables\Columns\TextColumn::make('upload_answer')->label('وضعیت پذیرش'),
            ])
            ->filters([
                // Your filters can go here
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
