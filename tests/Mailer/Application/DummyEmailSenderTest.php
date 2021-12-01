<?php

namespace App\Tests\Mailer\Application;

use App\Core\Domain\Email;
use App\Mailer\Application\EmailSender;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Uid\UuidV4;

class DummyEmailSenderTest extends WebTestCase
{
    public function testSendPasswordChangingEmail(): void
    {
        $client = static::createClient();
        $emailSender = static::getContainer()->get(EmailSender::class);

        $emailSender->sendInvitationEmail(new Email('test@test.pl'), 'Kacper', '12344312', UuidV4::v4()->toRfc4122());
        static::assertEmailCount(1);
    }
}
