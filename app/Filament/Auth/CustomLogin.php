<?php

namespace App\Filament\Auth;

use Filament\Forms\Components\Component;
use Filament\Forms\Components\TextInput;
use Filament\Pages\Auth\Login;
use Filament\Forms\Components\Checkbox;
use Filament\Notifications\Notification;
use Illuminate\Validation\ValidationException;
use Illuminate\Contracts\Validation\Validator;
use App\Models\BackgroundImage;
class CustomLogin extends Login
{
    /**
     * Get the form configuration for the login page.
     *
     * @return array
     */
    protected function getForms(): array
    {
        return [
            'form' => $this->form(
                $this->makeForm()
                    ->schema($this->getFormSchema())
                    ->statePath('data')
            ),
        ];
    }

    /**
     * Define the form schema.
     *
     * @return array
     */
    protected function getFormSchema(): array
    {
        return [
            $this->getLoginFormComponent(),
            $this->getPasswordFormComponent(),
            $this->getRememberFormComponent(),
        ];
    }

    /**
     * Custom login field with error handling.
     *
     * @return \Filament\Forms\Components\Component
     */
    protected function getLoginFormComponent(): Component
    {
        return TextInput::make('login')
            ->label(__('auth.username'))
            ->required()
            ->autocomplete('off')
            ->autofocus()
            ->extraInputAttributes(['tabindex' => 1])
            ->reactive();
    }

    /**
     * Password field.
     *
     * @return \Filament\Forms\Components\Component
     */
    protected function getPasswordFormComponent(): Component
    {
        return TextInput::make('password')
            ->label(__('auth.password'))
            ->required()
            ->password()
            ->autocomplete('off')
            ->extraInputAttributes(['tabindex' => 2]);
    }

    /**
     * Remember me checkbox.
     *
     * @return \Filament\Forms\Components\Component
     */
    protected function getRememberFormComponent(): Component
    {
        return Checkbox::make('remember')
            ->label(__('auth.remember'))
            ->extraInputAttributes(['tabindex' => 3]);
    }

    /**
     * Get the background URL dynamically.
     *
     * @return string
     */
    protected function getBackgroundUrl(): string
    {
        // You can replace this with fetching from DB or configuration file
        
        
        $background = BackgroundImage::latest()->first();
        if ($background) {
            return asset('storage/' . $background->image_path); // Use 'storage/' prefix to match symlinked directory
        }
        return asset('storage/images/default-background.jpg'); // Fallback image

    }

    /**
     * Get the credentials from form data.
     *
     * @param array $data
     * @return array
     */
    protected function getCredentialsFromFormData(array $data): array
    {
        return [
            'name' => $data['login'],
            'password' => $data['password'],
        ];
    }

    /**
     * Validate credentials with custom validation.
     *
     * @param array $credentials
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function validateCredentials(array $credentials)
    {
        $validator = $this->getCredentialsValidator($credentials);

        if ($validator->fails()) {
            throw ValidationException::withMessages($validator->errors()->toArray());
        }
    }

    /**
     * Create the validator for credentials.
     *
     * @param array $credentials
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function getCredentialsValidator(array $credentials)
    {
        return \Validator::make($credentials, [
            'name' => 'required|string|exists:users,name',
            'password' => 'required|string',
        ], [
            'name.required' => __('auth.username_required'),
            'name.exists' => __('auth.username_exists'),
            'password.required' => __('auth.password_required'),
        ]);
    }

    /**
     * Custom failure validation exception message.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function throwFailureValidationException(): never
    {
        throw ValidationException::withMessages([
            'data.login' => __('auth.login_failed'),
        ]);
    }

    /**
     * Send a notification after successful login.
     *
     * @return void
     */
    protected function sendLoginSuccessNotification()
    {
        Notification::make()
            ->title(__('auth.login_success'))
            ->success()
            ->send();
    }

    /**
     * Handle the successful login process.
     *
     * @return void
     */
    public function handleSuccessfulLogin()
    {
        $this->sendLoginSuccessNotification();
        // Additional logic for successful login can be added here
    }

    /**
     * Apply the background dynamically to the login page.
     *
     * @return void
     */
    public function applyBackground()
    {
        $backgroundUrl = $this->getBackgroundUrl();

        $this->viewData['background_url'] = $backgroundUrl;
    }

    /**
     * The login page's layout view
     *
     * @return string
     */
    public function layout(): string
    {
        $this->applyBackground();  // Apply the background dynamically

        return 'filament::auth.login';
    }
}
