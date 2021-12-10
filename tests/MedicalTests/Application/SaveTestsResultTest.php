<?php

declare(strict_types=1);

namespace App\Tests\MedicalTests\Application;

use App\Core\Domain\Email;
use App\MedicalTests\Application\SaveTestsResult;
use App\Tests\DoctrineTestCase;
use Symfony\Component\Uid\UuidV4;

class SaveTestsResultTest extends DoctrineTestCase
{
    private UuidV4 $userId;
    private UuidV4 $laboratoryWorkerId;

    public function setUp(): void
    {
        parent::setUp();

        $labId = $this->createNewLab(UuidV4::v4());

        $this->userId = $this->createActiveUser(
            id: UuidV4::v4(),
            email: new Email('test@patient.pl'),
            roles: ['ROLE_PATIENT'],
            activationTokenId: UuidV4::v4()
        );

        $this->laboratoryWorkerId = $this->createActiveUser(
            id: UuidV4::v4(),
            email: new Email('test@worker.pl'),
            roles: ['ROLE_LABORATORY_WORKER'],
            activationTokenId: UuidV4::v4(),
            laboratoryId: $labId
        );
    }

    public function testTestsResultWillBeSavedSuccessfullyForTheFirstTime(): void
    {
        $this->messageBus()->dispatch(new SaveTestsResult\Command(
            testId: $testId = UuidV4::v4(),
            userId: $this->userId,
            laboratoryWorkerId: $this->laboratoryWorkerId,
            status: 'in-progress',
            results: [
                [
                    'name' => 'Morfologia',
                    'icdCode' => 'ICD-9: C55',
                    'indicators' => [
                        [
                            'name' => 'Leukocyty',
                            'result' => 7.93,
                            'unit' => 'tys/mm3',
                            'referenceRange' => [
                                'min' => 4.2,
                                'max' => 9.0
                            ]
                        ],
                        [
                            'name' => 'Erytrocyty',
                            'result' => 5.50,
                            'unit' => 'mln/mm3',
                            'referenceRange' => [
                                'min' => 4.6,
                                'max' => 6.1
                            ]
                        ],
                        [
                            'name' => 'Hemoglobina',
                            'result' => 16.8,
                            'unit' => 'g/dl',
                            'referenceRange' => [
                                'min' => 13.0,
                                'max' => 18.0
                            ]
                        ]
                    ]
                ]
            ]
        ));

        $result = $this->connection()->fetchAllAssociative('select * from tests_results');

        static::assertNotEmpty($result);
        static::assertEquals($testId, Uuidv4::fromString($result[0]['id']));

        $testsResult = $this->testsResultRepository()->fetchTestsResultById($testId);

        static::assertCount(1, $testsResult->results());
    }

    public function testResulWillBeUpdated(): void
    {
        $this->messageBus()->dispatch(new SaveTestsResult\Command(
            testId: $testId = UuidV4::v4(),
            userId: $this->userId,
            laboratoryWorkerId: $this->laboratoryWorkerId,
            status: 'in-progress',
            results: [
                [
                    'name' => 'Morfologia',
                    'icdCode' => 'ICD-9: C55',
                    'indicators' => [
                        [
                            'name' => 'Leukocyty',
                            'result' => 7.93,
                            'unit' => 'tys/mm3',
                            'referenceRange' => [
                                'min' => 4.2,
                                'max' => 9.0
                            ]
                        ],
                    ]
                ]
            ]
        ));

        $this->messageBus()->dispatch(new SaveTestsResult\Command(
            testId: $testId,
            userId: $this->userId,
            laboratoryWorkerId: $this->laboratoryWorkerId,
            status: 'in-progress',
            results: [
                [
                    'name' => 'Morfologia',
                    'icdCode' => 'ICD-9: C55',
                    'indicators' => [
                        [
                            'name' => 'Leukocyty',
                            'result' => 7.93,
                            'unit' => 'tys/mm3',
                            'referenceRange' => [
                                'min' => 4.2,
                                'max' => 9.0
                            ]
                        ],
                        [
                            'name' => 'Erytrocyty',
                            'result' => 5.50,
                            'unit' => 'mln/mm3',
                            'referenceRange' => [
                                'min' => 4.6,
                                'max' => 6.1
                            ]
                        ],
                        [
                            'name' => 'Hemoglobina',
                            'result' => 16.8,
                            'unit' => 'g/dl',
                            'referenceRange' => [
                                'min' => 13.0,
                                'max' => 18.0
                            ]
                        ]
                    ]
                ]
            ]
        ));

        $result = $this->connection()->fetchAllAssociative('select * from tests_results');

        static::assertNotEmpty($result);
        static::assertEquals($testId, Uuidv4::fromString($result[0]['id']));

        $testsResult = $this->testsResultRepository()->fetchTestsResultById($testId);

        static::assertCount(1, $testsResult->results());
    }
}
