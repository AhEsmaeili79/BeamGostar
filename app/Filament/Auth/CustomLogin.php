<?php

namespace App\Filament\Auth;

use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\Component;
use Filament\Forms\Components\TextInput;
use Filament\Pages\Auth\Login;
use Illuminate\Validation\ValidationException;
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

    /**
     * Create the login form field.
     */
    protected function getLoginFormComponent(): Component
    {
        return TextInput::make('login')
            ->label(__('نام کاربری'))
            ->required()
            ->autocomplete('off')
            ->autofocus()
            ->extraInputAttributes(['tabindex' => 1])
            ->reactive();
    }

    /**
     * Create the password form field.
     */
    protected function getPasswordFormComponent(): Component
    {
        return TextInput::make('password')
            ->label(__('رمز عبور'))
            ->required()
            ->password()
            ->autocomplete('off')
            ->extraInputAttributes(['tabindex' => 2]);
    }

    /**
     * Create the "remember me" checkbox.
     */
    protected function getRememberFormComponent(): Component
    {
        return Checkbox::make('remember')
            ->label(__('مرا به خاطر بسپار'))
            ->extraInputAttributes(['tabindex' => 3]);
    }

    /**
     * Extract credentials from form data.
     */
    protected function getCredentialsFromFormData(array $data): array
    {
        return [
            'name' => $data['login'], // Map 'login' field to the 'name' field in the database
            'password' => $data['password'],
        ];
    }

    /**
     * Validate the provided credentials.
     */
    protected function validateCredentials(array $credentials): void
    {
        $validator = \Validator::make($credentials, [
            'name' => 'required|string|exists:users,name', // Ensure the username exists
            'password' => 'required|string',
        ], [
            'name.required' => __('نام کاربری را وارد کنید'),
            'name.exists' => __('نام کاربری وارد شده وجود ندارد'),
            'password.required' => __('رمز عبور را وارد کنید'),
        ]);

        if ($validator->fails()) {
            throw ValidationException::withMessages($validator->errors()->toArray());
        }
    }

    /**
     * Throw a validation exception for invalid credentials.
     */
    protected function throwFailureValidationException(): never
    {
        throw ValidationException::withMessages([
            'data.login' => __('نام کاربری یا رمز عبور اشتباه است'),
        ]);
    }

    /**
     * Send a notification upon successful login.
     */
    protected function sendLoginSuccessNotification(): void
    {
        Notification::make()
            ->title(__('ورود موفق'))
            ->success()
            ->send();
    }

    /**
     * Handle additional logic for a successful login.
     */
    public function handleSuccessfulLogin(): void
    {
        $this->sendLoginSuccessNotification();
        // Add custom logic for successful login, if necessary
    }
}
