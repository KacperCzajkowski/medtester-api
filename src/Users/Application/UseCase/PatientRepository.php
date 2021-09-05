<?php

declare(strict_types=1);

namespace App\Users\Application\UseCase;

use App\Core\Domain\Email;
use App\Users\Domain\Patient;

interface PatientRepository
{
    public function findPatientByEmail(Email $email): ?Patient;

    public function addPatient(Patient $patient): void;
}
