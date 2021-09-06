<?php

declare(strict_types=1);

namespace App\Core\Domain;

use Egulias\EmailValidator\EmailValidator;
use Egulias\EmailValidator\Validation\DNSCheckValidation;
use Egulias\EmailValidator\Validation\MultipleValidationWithAnd;
use Egulias\EmailValidator\Validation\RFCValidation;
use Symfony\Component\String\Exception\InvalidArgumentException;

final class Email implements \JsonSerializable
{
    private string $email;

    public function __construct(string $email)
    {
        $email = trim($email);
        $email = mb_strtolower($email);
        if (!self::isValid($email)) {
            throw new InvalidArgumentException(sprintf('"%s" is not valid email', $email));
        }

        $this->email = $email;
    }

    public static function isValid(string $email, bool $checkDns = false): bool
    {
        $validator = new EmailValidator();

        $validation = new RFCValidation();
        if ($checkDns) {
            $validation = new MultipleValidationWithAnd([
                new RFCValidation(),
                new DNSCheckValidation(),
            ]);
        }

        return $validator->isValid($email, $validation);
    }

    public function value(): string
    {
        return $this->email;
    }

    public function __toString(): string
    {
        return $this->email;
    }

    /**
     * @return array{email: string}
     */
    public function jsonSerialize(): array
    {
        return [
            'email' => $this->email,
        ];
    }

    public function isEquals(Email $secondEmail): bool
    {
        return $this->email === $secondEmail->value();
    }
}
