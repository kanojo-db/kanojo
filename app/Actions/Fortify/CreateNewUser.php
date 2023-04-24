<?php

declare(strict_types=1);

namespace App\Actions\Fortify;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Laravel\Fortify\Contracts\CreatesNewUsers;
use Laravel\Fortify\Rules\Password;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules;

    /**
     * Validate and create a newly registered user.
     *
     * @param  array{email: string, name: string, password: string}  $input
     */
    public function create(array $input): User
    {
        Validator::make($input, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => $this->passwordRules(),
        ])->validate();

        $user = User::create([
            'name' => $input['name'],
            'email' => $input['email'],
            'password' => Hash::make($input['password']),
        ]);

        return $user;
    }

    /**
     * Get the validation rules used to validate passwords.
     *
     * @return array<array-key, \Laravel\Fortify\Rules\Password|string>
     */
    protected function passwordRules(): array
    {
        return ['required', 'string', new Password];
    }
}
