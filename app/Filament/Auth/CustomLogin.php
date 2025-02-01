<?php

namespace App\Filament\Auth;

use Filament\Forms\Components\Component;
use Filament\Forms\Components\TextInput;
use Filament\Pages\Auth\Login;
use Illuminate\Validation\ValidationException;
use Filament\Forms\Components\Checkbox;
use Filament\Facades\Notification;

class CustomLogin extends Login
{
    protected function getForms(): array
    {
        return [
            'form' => $this->form(
                $this->makeForm()
                    ->schema([
                        $this->getLoginFormComponent(),
                        $this->getPasswordFormComponent(),
                        $this->getRememberFormComponent(),
                    ])
                    ->statePath('data')
            ),
        ];
    }

    // Custom login field with error handling
    protected function getLoginFormComponent(): Component
    {
        return TextInput::make('login')
            ->label(__('auth.username'))
            ->required()
            ->autocomplete('off')
            ->autofocus()
            ->extraInputAttributes(['tabindex' => 1])
            ->reactive(); // Enables real-time validation updates
    }

    // Password field
    protected function getPasswordFormComponent(): Component
    {
        return TextInput::make('password')
            ->label(__('auth.password'))
            ->required()
            ->password()
            ->autocomplete('off')
            ->extraInputAttributes(['tabindex' => 2]);
    }

    // Remember me checkbox
    protected function getRememberFormComponent(): Component
    {
        return Checkbox::make('remember')
            ->label(__('auth.remember'))
            ->extraInputAttributes(['tabindex' => 3]);
    }

    // Credentials extracted from the form data
    protected function getCredentialsFromFormData(array $data): array
    {
        return [
            'name' => $data['login'], // Correct field name to 'login' for the username
            'password' => $data['password'],
        ];
    }

    // Custom error handling with Laravel validation
    protected function validateCredentials(array $credentials)
    {
        $validator = \Validator::make($credentials, [
            'name' => 'required|string|exists:users,name', // Adjust according to your database
            'password' => 'required|string',
        ], [
            'name.required' => __('auth.username_required'),
            'name.exists' => __('auth.username_exists'),
            'password.required' => __('auth.password_required'),
        ]);

        if ($validator->fails()) {
            throw ValidationException::withMessages($validator->errors()->toArray());
        }
    }

    // Custom failure validation exception
    protected function throwFailureValidationException(): never
    {
        throw ValidationException::withMessages([
            'data.login' => __('auth.login_failed'), // Custom error message for username/password mismatch
        ]);
    }

    // Custom success notification after successful login
    protected function sendLoginSuccessNotification()
    {
        Notification::make()
            ->title(__('auth.login_success'))
            ->success()
            ->send();
    }

    // Handle the successful login process
    public function handleSuccessfulLogin()
    {
        $this->sendLoginSuccessNotification();
        // Additional logic for successful login
    }
}
