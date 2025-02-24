<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ReturnRequestResource\Pages;
use App\Models\ReturnRequest;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Actions\Action;
use Morilog\Jalali\Jalali;

class ReturnRequestResource extends Resource
{
    protected static ?string $model = ReturnRequest::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    // Move dynamic expressions like this one into methods
    protected static ?string $navigationLabel = null; // to be set by a method


    protected static ?string $pluralModelLabel = null; // to be set by a method

    protected static ?string $label = null; // to be set by a method

    protected static ?string $pluralLabel = null; // to be set by a method

    protected static ?string $singularLabel = null; // to be set by a method

    protected static ?string $slug = 'rejects';
    
    protected static ?int $navigationSort = 4;

    // Method to set navigation label dynamically
    public static function getNavigationGroup(): string
    {
        return __('filament.labels.admissions');  // Make sure you add this translation to the files
    }

    public static function getNavigationLabel(): string
    {
        return __('filament.labels.reject');
    }

    public static function getPluralModelLabel(): string
    {
        return __('filament.labels.reject');
    }

    public static function getLabel(): string
    {
        return __('filament.labels.reject');
    }

    public static function getPluralLabel(): string
    {
        return __('filament.labels.reject');
    }

    public static function getSingularLabel(): string
    {
        return __('filament.labels.reject');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('tracking_code')
                    ->label('کد پیگیری')
                    ->disabled(),
                // Forms\Components\Select::make('customer_analysis_id')
                //     ->label('تاریخ پذیرش')
                //     ->relationship('customerAnalysis', 'acceptance_date')
                //     ->getOptionLabelUsing(fn ($value) => Jalali::fromCarbon($value)->format('Y/m/d')) // Format the date to Jalali
                //     ->disabled(),
                Forms\Components\Select::make('customer_analysis_id')
                ->label('تعداد نمونه')
                ->relationship('customerAnalysis', 'samples_number')
                ->disabled(),
                Forms\Components\Select::make('customer_analysis_id')
                ->label('زمان تحلیل')
                ->relationship('customerAnalysis', 'analyze_time')
                ->disabled(),
                Forms\Components\Select::make('customer_analysis_id')
                ->label('هزینه کل')
                ->relationship('customerAnalysis', 'total_cost')
                ->disabled(),
                Forms\Components\Select::make('customer_analysis_id')
                ->label('وضعیت آنالیز')
                ->relationship('customerAnalysis', 'status')
                ->disabled(),
                Forms\Components\Select::make('customer_analysis_id')
                ->label('سهم درخواست کننده')
                ->relationship('customerAnalysis', 'applicant_share')
                ->disabled(),
                Forms\Components\Select::make('customer_analysis_id')
                ->label('شناسه روش پرداخت')
                ->relationship('customerAnalysis', 'payment_method_id')
                ->disabled(),
                
                Forms\Components\Select::make('status')
                    ->label('وضعیت')
                    ->options([
                        'requested' => 'درخواست عودت',
                        'canceled' => 'لغو شده',
                        'ready_for_return' => 'آماده جهت عودت',
                        'returned' => 'عودت شده',
                    ])
                    ->disabled(),
                Forms\Components\Textarea::make('rejection_reason')
                    ->label('دلیل رد')
                    ->nullable()
                    ->disabled(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('tracking_code')->label('کد پیگیری'),
                TextColumn::make('customerAnalysis.customer.fullNameFa')->label('نام مشتری'),
                TextColumn::make('customerAnalysis.acceptance_date')->label('تاریخ پذیرش'),
                TextColumn::make('customerAnalysis.analyze.title')->label('آنالیز'),
                TextColumn::make('customerAnalysis.samples_number')->label('تعداد نمونه'),
                TextColumn::make('customerAnalysis.total_cost')->label('هزینه کل'),
                TextColumn::make('customerAnalysis.applicant_share')->label('سهم درخواست کننده'),
                TextColumn::make('customerAnalysis.priority')->label('اولویت'),
                TextColumn::make('status')
                    ->label('وضعیت')
                    ->formatStateUsing(fn ($state) => match ($state) {
                        'requested' => 'درخواست عودت',
                        'canceled' => 'لغو شده',
                        'ready_for_return' => 'آماده جهت عودت',
                        'returned' => 'عودت شده',
                    }),
                TextColumn::make('rejection_reason')->label('دلیل رد')->limit(30),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Action::make('review')
                    ->label('بررسی')
                    ->form([
                        Forms\Components\Radio::make('approve')
                            ->label('تایید درخواست')
                            ->options([
                                1 => 'بله',
                                0 => 'خیر',
                            ])
                            ->required(),
                        Forms\Components\Textarea::make('rejection_reason')
                            ->label('دلیل رد')
                            ->visible(fn ($get) => $get('approve') == 0),
                    ])
                    ->action(function (array $data, ReturnRequest $record) {
                        if ($data['approve']) {
                            $record->update(['status' => 'ready_for_return']);
                        } else {
                            $record->update(['rejection_reason' => $data['rejection_reason']]);
                            // Send SMS (Commented out for now)
                            // Notification::send($record->customerAnalysis->customer, new ReturnRequestRejected($record->rejection_reason));
                        }
                    }),

                Action::make('confirm_return')
                    ->label('تایید عودت')
                    ->requiresConfirmation()
                    ->action(fn (ReturnRequest $record) => $record->update(['status' => 'returned']))
                    ->visible(fn (ReturnRequest $record) => $record->status === 'ready_for_return'),
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
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListReturnRequests::route('/'),
        ];
    }
}
