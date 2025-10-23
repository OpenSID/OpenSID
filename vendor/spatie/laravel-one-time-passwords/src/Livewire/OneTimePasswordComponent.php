<?php

namespace Spatie\OneTimePasswords\Livewire;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\RateLimiter;
use Livewire\Component;
use Spatie\OneTimePasswords\Models\Concerns\HasOneTimePasswords;
use Spatie\OneTimePasswords\Rules\OneTimePasswordRule;

class OneTimePasswordComponent extends Component
{
    public ?string $email = null;

    public string $oneTimePassword = '';

    public bool $isFixedEmail = false;

    public string $redirectTo = '/';

    public bool $displayingEmailForm = true;

    public function mount(?string $redirectTo = null, ?string $email = ''): void
    {
        $this->email = $email;

        if ($this->email) {
            $this->isFixedEmail = true;
            $this->displayingEmailForm = false;
        }

        $this->redirectTo = $redirectTo
            ?? config('one-time-passwords.redirect_successful_authentication_to');
    }

    public function submitEmail(): void
    {
        $this->validate([
            'email' => 'required|email',
        ]);

        $user = $this->findUser();

        if (! $user) {
            $this->addError('email', 'We could not find a user with that email address.');

            return;
        }

        $this->sendCode();

        $this->displayingEmailForm = false;
    }

    public function resendCode(): void
    {
        $this->sendCode();
    }

    protected function sendCode(): void
    {
        $user = $this->findUser();

        if ($this->rateLimitHit()) {
            return;
        }

        $this->displayingEmailForm = false;

        $user->sendOneTimePassword();
    }

    protected function rateLimitHit(): bool
    {
        $rateLimitKey = "one-time-password-component-send-code.{$this->email}";

        if (RateLimiter::tooManyAttempts($rateLimitKey, 10)) {
            return true;
        }

        RateLimiter::hit($rateLimitKey, 60); // 60 seconds decay time

        return false;
    }

    public function displayEmailForm(): void
    {
        $this->email = null;

        $this->displayingEmailForm = true;
    }

    public function submitOneTimePassword()
    {
        $user = $this->findUser();

        $this->validate([
            'oneTimePassword' => ['required', new OneTimePasswordRule($user)],
        ]);

        $this->authenticate($user);

        return $this->redirect($this->redirectTo);
    }

    public function render(): View
    {
        return view("one-time-passwords::livewire.{$this->showViewName()}");
    }

    /**
     * @return HasOneTimePasswords&Model&Authenticatable
     */
    protected function findUser(): ?Authenticatable
    {
        $authenticatableModel = config('auth.providers.users.model');

        return $authenticatableModel::firstWhere('email', $this->email);
    }

    public function authenticate(Authenticatable $user): void
    {
        auth()->login($user);
    }

    public function showViewName(): string
    {
        return $this->displayingEmailForm
            ? 'email-form'
            : 'one-time-password-form';
    }
}
