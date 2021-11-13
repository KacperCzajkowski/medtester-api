<?php

namespace App\Tests\Mailer\Application;

use App\Core\Domain\Email;
use App\Mailer\Application\EmailSender;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class DummyEmailSenderTest extends WebTestCase
{
    public function testSendPasswordChangingEmail(): void
    {
        $client = static::createClient();
        $emailSender = static::getContainer()->get(EmailSender::class);

        $emailSender->sendEmailWithNewPassword(new Email('test@test.pl'), 'Kacper', '12344312');
        static::assertEmailCount(1);
    }
}
