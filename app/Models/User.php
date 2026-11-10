<?php

namespace App\Models;

use App\Models\Concerns\HasRoles;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

use LdapRecord\Laravel\Auth\LdapAuthenticatable;
use LdapRecord\Laravel\Auth\AuthenticatesWithLdap;

use Laravel\Fortify\TwoFactorAuthenticatable;

#[Fillable(['name', 'email', 'password' ,'username','guid','usuario_unoee','clave_unoee'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable implements MustVerifyEmail , LdapAuthenticatable
{
    use HasRoles;
    use AuthenticatesWithLdap;
    use TwoFactorAuthenticatable;

    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;


    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'clave_unoee' => 'encrypted',
        ];
    }
}
