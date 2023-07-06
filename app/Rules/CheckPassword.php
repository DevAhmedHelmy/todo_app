<?php

namespace App\Rules;

use App\Models\User;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class CheckPassword implements ValidationRule
{
    protected $username;

    public function __construct($username)
    {
        $this->username = $username;
    }

    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $user = $this->getUser();
        if (! $user || ! password_verify($value, $user->password)) {
            $fail(':attribute mismatch');
        }
    }

    private function getUser()
    {
        $fieldType = filter_var($this->username, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        return User::where($fieldType, $this->username)->first();

    }
}
