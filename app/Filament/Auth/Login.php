<?php

namespace App\Filament\Auth;

use Filament\Forms\Components\Component;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Http\Responses\Auth\LoginResponse;
use Filament\Pages\Auth\Login as AuthLogin;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\HtmlString;
use Illuminate\Validation\ValidationException;

class Login extends AuthLogin
{

    public function getHeading(): string|Htmlable
    {
        return new HtmlString('<div class="pt-2">E-SETDA PARKIR</div>');
    }
    /**
     * Get the form for the resource.
     */
    public function form(Form $form): Form
    {
        return $form
            ->schema([
                $this->getUsernameFormComponent(),
                $this->getPasswordFormComponent(),
                $this->getRememberFormComponent(),
            ])
            ->statePath('data');
    }

    /**
     * Get the name form component.
     */
    protected function getUsernameFormComponent(): Component
    {
        return TextInput::make('name')
            ->label('Username')
            ->required()
            ->autocomplete()
            ->autofocus()
            ->extraInputAttributes(['tabindex' => 1]);
    }

    /**
     * Get the credentials from the form data.
     */
    protected function getCredentialsFromFormData(array $data): array
    {
        $type = filter_var($data['name'], FILTER_VALIDATE_EMAIL) ? 'email' : 'name';
        return [
            $type => $data['name'],
            'password' => $data['password'],
        ];
    }

    /**
     * Attempt to authenticate the user.
     */
    public function authenticate(): ?LoginResponse
    {
        try {
            return parent::authenticate();
        } catch (ValidationException) {
            throw ValidationException::withMessages([
                'data.name' => __('filament-panels::pages/auth/login.messages.failed'),
            ]);
        }
    }
}
