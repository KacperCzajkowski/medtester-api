<?php

namespace App\Tests;

use App\Core\Domain\Clock;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Doctrine\DBAL\Connection;
use Doctrine\ORM\EntityManager;
use Symfony\Bridge\Doctrine\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Messenger\MessageBusInterface;

class DoctrineTestCase extends KernelTestCase
{
    /**
     * @group legacy
     */
    public function setUp(): void
    {
        parent::setUp();
        self::bootKernel();

        /**
         * @var ManagerRegistry $doctrine
         */
        $doctrine = self::$container->get('doctrine');

        (new ORMPurger($doctrine->getManager()))->purge();
    }

    protected function messageBus(): MessageBusInterface
    {
        $container = self::$container;

        return $container->get(MessageBusInterface::class);
    }

    protected function connection(): Connection
    {
        $container = self::$container;

        return $container->get('doctrine.dbal.default_connection');
    }

    protected function entityManager(): EntityManager
    {
        $container = self::$container;

        return $container->get('doctrine')->getManager();
    }

    protected function clock(): Clock
    {
        $container = self::$container;

        return $container->get('App\Core\Domain\Clock\Clock');
    }
}
