<?php

declare(strict_types=1);

namespace App\Behat;

use App\Core\Domain\Clock;
use App\Core\Domain\Email;
use App\Core\Domain\SystemId;
use App\Users\Application\UseCase\CreateUser;
use App\Users\Domain\User;
use App\Users\Domain\UserRepository;
use Behat\Behat\Context\Environment\InitializedContextEnvironment;
use Behat\Behat\Hook\Scope\BeforeScenarioScope;
use Behat\Gherkin\Node\PyStringNode;
use Behat\Gherkin\Node\TableNode;
use Behat\MinkExtension\Context\MinkContext;
use Behatch\Context\RestContext;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Doctrine\DBAL\Connection;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Uid\UuidV4;

/**
 * Defines application features from the specific context.
 */
class FeatureContext extends MinkContext
{
    private const DEFAULT_PASSWORD = 'test1234';

    private RestContext $restContext;

    public function __construct(
        private EntityManagerInterface $entityManager,
        private MessageBusInterface $messageBus,
        private Clock $clock,
        private ManagerRegistry $managerRegistry,
        private UserRepository $userRepository
    ) {
    }

    /**
     * @BeforeScenario
     */
    public function reset(): void
    {
        $purger = new ORMPurger($this->entityManager);
        $purger->purge();
    }

    /**
     * @BeforeScenario
     */
    public function gatherContexts(BeforeScenarioScope $scope): void
    {
        /**
         * @var InitializedContextEnvironment $environment
         */
        $environment = $scope->getEnvironment();

        /**
         * @var RestContext $restContext
         */
        $restContext = $environment->getContext(RestContext::class);

        $this->restContext = $restContext;
        $session = $this->restContext->getMink()->getSession();
        $session->reset();

        /**
         * @var KernelBrowser $client
         * @psalm-suppress UndefinedInterfaceMethod
         */
        $client = $session->getDriver()->getClient();
        $client->disableReboot();
    }

    /**
     * @Given /^"([^"]*)" is logged in using "([^"]*)" password$/
     */
    public function isLoggedInUsingPassword(string $email, string $password): void
    {
        $this->restContext->iAddHeaderEqualTo('Content-Type', 'application/json');
        $this->restContext->iAddHeaderEqualTo('Accept', 'application/json');
        $this->restContext->iSendARequestToWithBody(
            'POST',
            '/login',
            new PyStringNode(
                explode(
                    "\n",
                    json_encode(['username' => $email, 'password' => $password])
                ),
                1
            )
        );
        $this->restContext->assertSession()->statusCodeEquals(200);
    }

    /**
     * @Given /^there are laboratory workers is system:$/
     */
    public function thereAreLaboratoryWorkersIsSystem(TableNode $table): void
    {
        /**
         * @var ObjectManager $manager
         */
        $manager = $this->managerRegistry->getManagerForClass(User::class);

        foreach ($table->getHash() as $hash) {
            $command = new CreateUser\Command(
                UuidV4::fromString($hash['id']),
                $hash['firstName'],
                $hash['lastName'],
                $hash['email'],
                [User::ROLES['ROLE_LABORATORY_WORKER']],
                UuidV4::fromString($hash['createdBy']),
                $hash['pesel'],
                $hash['gender']
            );

            $command->setLaboratoryId(UuidV4::fromString($hash['laboratoryId']));

            $this->messageBus->dispatch($command);

            /**
             * @var User $user
             */
            $user = $this->userRepository->findUserByEmail(new Email($hash['email']));

            $user->setPassword(self::DEFAULT_PASSWORD);
            $manager->persist($user);
        }

        $manager->flush();
    }

    /**
     * @Given /^there are admins in system:$/
     */
    public function thereAreAdminsInSystem(TableNode $table): void
    {
        /**
         * @var ObjectManager $manager
         */
        $manager = $this->managerRegistry->getManagerForClass(User::class);

        foreach ($table->getHash() as $hash) {
            $command = new CreateUser\Command(
                UuidV4::fromString($hash['id']),
                $hash['firstName'],
                $hash['lastName'],
                $hash['email'],
                [User::ROLES['ROLE_SYSTEM_ADMIN']],
                SystemId::asUuidV4(),
                $hash['pesel'],
                $hash['gender']
            );

            $this->messageBus->dispatch($command);

            /**
             * @var User $user
             */
            $user = $this->userRepository->findUserByEmail(new Email($hash['email']));

            $user->setPassword(self::DEFAULT_PASSWORD);
            $manager->persist($user);
        }

        $manager->flush();
    }

    private function connection(): Connection
    {
        /**
         * @var Connection $connection
         */
        $connection = $this->managerRegistry->getConnection();

        return $connection;
    }
}
