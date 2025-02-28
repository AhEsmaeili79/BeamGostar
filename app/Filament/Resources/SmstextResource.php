<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SmstextResource\Pages;
use App\Models\Smstext;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\TextArea;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Select;
use Illuminate\Support\Facades\Lang;
use Morilog\Jalali\Jalalian;
class SmstextResource extends Resource
{
    protected static ?string $model = Smstext::class;

    protected static ?string $navigationLabel = 'متن پیامک';

    public static function getNavigationGroup(): string
    {
        return __('filament.labels.base_info');
    }

    protected static ?string $pluralLabel =  'متن پیامک';
    protected static ?string $singularLabel = 'متن پیامک';

    protected static ?string $label = 'متن پیامک';

    protected static ?string $navigationIcon = 'heroicon-o-chat-bubble-left-right';

    protected static ?int $navigationSort = 13;
    /**
     * Define the form schema for SMS texts.
     *
     * @param Form $form
     * @return Form
     */
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('stage_level')
                    ->label(__('filament.labels.Stage_Level'))
                    ->required()
                    ->options([
                        0 => __('filament.labels.awaiting_payment'),
                        1 => __('filament.labels.full_acceptance'),
                        2 => __('filament.labels.awaiting'),
                        3 => __('filament.labels.canceled'),
                        4 => __('filament.labels.financial_approval'),
                        5 => __('filament.labels.awaiting_technical_management_approval'),
                        6 => __('filament.labels.technical_management_approval'),
                        7 => __('filament.labels.analysis_complete'),
                        8 => __('filament.labels.awaiting_financial_management_approval'),
                    ]),

                TextArea::make('text')
                    ->label(__('filament.labels.Text'))
                    ->required()
                    ->maxLength(2000) // Increase max length for larger text input
                    ->rows(6), // Makes the textarea larger
               
                Toggle::make('status')
                    ->label(__('filament.labels.Active'))
                    ->default(1)
                    ->required(),
            ]);
    }

    /**
     * Define the table columns for SMS texts.
     *
     * @param Table $table
     * @return Table
     */
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('stage_level')
                    ->label(__('filament.labels.Stage_Level'))
                    ->sortable()
                    ->searchable()
                    ->formatStateUsing(fn ($state) => match ($state) {
                        0 => __('filament.labels.awaiting_payment'),
                        1 => __('filament.labels.full_acceptance'),
                        2 => __('filament.labels.awaiting'),
                        3 => __('filament.labels.canceled'),
                        4 => __('filament.labels.financial_approval'),
                        5 => __('filament.labels.awaiting_technical_management_approval'),
                        6 => __('filament.labels.technical_management_approval'),
                        7 => __('filament.labels.analysis_complete'),
                        8 => __('filament.labels.awaiting_financial_management_approval'),
                        default => __('filament.labels.unknown'),
                    }),

                Tables\Columns\TextColumn::make('text')
                    ->label(__('filament.labels.Text'))
                    ->limit(100) // Adjusted to show more characters
                    ->searchable()
                    ->width('50%'), // This will make the column wider
               
                Tables\Columns\BooleanColumn::make('status')
                    ->label(__('filament.labels.Active'))
                    ->sortable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->sortable()
                    ->label(__('filament.labels.Created_At'))
                    ->formatStateUsing(fn ($state) => 
                    app()->getLocale() === 'fa' 
                        ? Jalalian::fromDateTime($state)->format('Y/m/d H:i') // Convert to Jalali
                        : \Carbon\Carbon::parse($state)->format('Y-m-d H:i') // Gregorian format
                )
                ->sortable(),

                Tables\Columns\TextColumn::make('updated_at')
                    ->sortable()
                    ->label(__('filament.labels.Updated_At')),
            ])
            ->filters([
                Tables\Filters\Filter::make('Active')
                    ->query(fn ($query) => $query->where('status', 1)),
                Tables\Filters\Filter::make('Inactive')
                    ->query(fn ($query) => $query->where('status', 0)),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    /**
     * Get the relations for the resource.
     *
     * @return array
     */
    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    /**
     * Define the available pages for this resource.
     *
     * @return array
     */
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSmstexts::route('/'),
            'create' => Pages\CreateSmstext::route('/create'),
            'edit' => Pages\EditSmstext::route('/{record}/edit'),
        ];
    }
}
