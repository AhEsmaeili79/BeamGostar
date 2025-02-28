<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Models\User;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Tables\Actions\Action;
use Rawilk\FilamentPasswordInput\Password;
use Filament\Notifications\Notification;
class UserResource extends Resource
{
    protected static ?string $model = User::class;
    
    public static function getNavigationGroup(): string
    {
        return __('filament.labels.user_management');
    }

    public static function getPluralLabel(): string
    {
        return __('filament.labels.users');
    }

    public static function getNavigationLabel(): string
    {
        return __('filament.labels.users');
    }

    public static function getLabel(): string
    {
        return __('filament.labels.users');
    }

    protected static ?string $navigationIcon = 'heroicon-o-user';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                
                TextInput::make('name')
                    ->label(__('filament.labels.username'))
                    ->required()
                    ->maxLength(50)
                    ->reactive()
                    ->disabled(), // Change the route name to your edit route


                Select::make('roles')
                    ->label(__('filament.labels.roles'))
                    ->relationship('roles', 'name')
                    ->multiple()
                    ->preload()
                    ->searchable(),

                Password::make('password')
                    ->label(__('filament.labels.password'))
                    ->required()
                    ->password()
                    ->hiddenOn(['edit'])
                    ->maxLength(50)
                    ->reactive(),

                Password::make('re_password')
                    ->label(__('filament.labels.password'))
                    ->required()
                    ->password()
                    ->hiddenOn(['edit'])
                    ->same('password')
                    ->maxLength(50)
                    ->reactive(),
            ])
            ->extraAttributes([
                'class' => 'filament-form-wrapper', // Adding a wrapper class for custom styles
                'style' => 'border: 3px solid #ddd; 
                            padding: 20px; 
                            border-radius: 12px; 
                            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1); 
                            transition: all 0.3s ease;',
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label(__('filament.labels.row'))
                    ->sortable()
                    ->wrap(),

                TextColumn::make('customer_full_name')
                    ->label(__('filament.labels.full_name'))
                    ->getStateUsing(function ($record) {
                        // Same logic for customer and personnel full name
                        $fullName = trim(($record->customer ? ($record->customer->name_fa . ' ' . $record->customer->family_fa) : '') 
                                    ?: ($record->personnel ? ($record->personnel->name . ' ' . $record->personnel->family) : ''));
                        return $fullName ?: '-';
                    })
                    ->wrap(),

                TextColumn::make('name')
                    ->label(__('filament.labels.username')),

                TextColumn::make('roles.name')
                    ->label(__('filament.labels.working_group'))
                    ->wrap(),
            ])
            ->filters([
                // Optional filters
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                // Add the "reset password" action here
                 // Reset Password action with modal confirmation
                 Action::make('resetPassword')
                 ->label('بازنشانی رمز عبور')
                 ->color('success')
                 ->modalHeading('تایید بازنشانی رمز عبور')
                ->modalSubheading('آیا مطمئن هستید که می‌خواهید رمز عبور را به نام کاربری تنظیم کنید؟')
                ->modalButton('بله، بازنشانی رمز عبور')
                 ->action(function (User $record) {
                     // Reset the password to the user's name (bcrypt hashed)
                     $record->password = bcrypt($record->name);
                     $record->save();

                     // Show notification
                     Notification::make()
                     ->title('بازنشانی رمز عبور')
                     ->success()
                     ->body('رمز عبور با موفقیت به نام کاربری تنظیم شد.')
                         ->send();
                 }),
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
            // Add relations if any
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
