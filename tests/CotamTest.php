<?php

namespace App\Tests;

use Doctrine\DBAL\Connection;
use PHPUnit\Framework\TestCase;
use Symfony\Bridge\Doctrine\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class CotamTest extends KernelTestCase
{
    public function setUp(): void
    {
        parent::setUp();
        self::bootKernel();
    }

    public function testCostam(): void
    {
        /**
         * @var Connection $tmp
         */
        $tmp = self::getContainer()->get('doctrine.dbal.default_connection');
        die;
        self::assertTrue(true);
    }
}
