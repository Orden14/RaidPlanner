<?php

namespace App\Util\Security;

use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;

final readonly class PasswordConstraintUtil
{
    /**
     * @return array<int, mixed>
     */
    public static function getPasswordConstraints(): array
    {
        return [
            new NotBlank([
                'message' => 'Veuillez renseigner un nouveau mot de passe',
            ]),
            new Length([
                'min' => 10,
                'minMessage' => 'Votre mot de passe doit contenir au moins {{ limit }} caractères.',
                'max' => 100,
            ]),
            new Regex([
                'pattern' => '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[a-zA-Z\d]{10,}$/',
                'message' => 'Votre mot de passe doit contenir au moins une lettre majuscule, une lettre minuscule et un chiffre.'
            ])
        ];
    }
}
