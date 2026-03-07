<?php

namespace Tests\Unit\Application\Advertisement\Services;

use App\Application\Advertisement\DTO\CreateAdvertisementDto;
use App\Application\Advertisement\Services\AdvertisementService;
use App\Domain\Advertisement\Entities\Advertisement;
use App\Domain\Advertisement\Entities\AdvertisementPhoto;
use App\Domain\Advertisement\Repositories\AdvertisementRepositoryInterface;
use App\Domain\Catalog\Entities\CatalogNode;
use App\Domain\Catalog\Repositories\CatalogReadRepositoryInterface;
use InvalidArgumentException;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class AdvertisementServiceTest extends TestCase
{
    public function test_create_saves_normalized_advertisement(): void
    {
        $catalogRepository = $this->createMock(CatalogReadRepositoryInterface::class);
        $catalogRepository->expects($this->once())
            ->method('findById')
            ->with(10)
            ->willReturn(new CatalogNode(10, 'Electronics', null));

        $expectedResult = new Advertisement(
            id: 100,
            userId: 12,
            catalogId: 10,
            title: 'iPhone 14',
            description: 'Состояние отличное',
            photos: [
                new AdvertisementPhoto('/img/1.jpg', 0),
                new AdvertisementPhoto('/img/2.jpg', 1),
            ],
        );

        $advertisementRepository = $this->createMock(AdvertisementRepositoryInterface::class);
        $advertisementRepository->expects($this->once())
            ->method('save')
            ->with($this->callback(function (Advertisement $advertisement): bool {
                $this->assertNull($advertisement->id);
                $this->assertSame(12, $advertisement->userId);
                $this->assertSame(10, $advertisement->catalogId);
                $this->assertSame('iPhone 14', $advertisement->title);
                $this->assertSame('Состояние отличное', $advertisement->description);
                $this->assertCount(2, $advertisement->photos);
                $this->assertSame('/img/1.jpg', $advertisement->photos[0]->path);
                $this->assertSame(0, $advertisement->photos[0]->sortOrder);
                $this->assertSame('/img/2.jpg', $advertisement->photos[1]->path);
                $this->assertSame(1, $advertisement->photos[1]->sortOrder);

                return true;
            }))
            ->willReturn($expectedResult);

        $service = new AdvertisementService($advertisementRepository, $catalogRepository);

        $result = $service->create(new CreateAdvertisementDto(
            userId: 12,
            catalogId: 10,
            title: '  iPhone 14  ',
            description: '  Состояние отличное  ',
            photos: ['  /img/1.jpg  ', ' /img/2.jpg '],
        ));

        $this->assertSame($expectedResult, $result);
    }

    #[DataProvider('invalidCreateDtoProvider')]
    public function test_create_throws_for_invalid_input(CreateAdvertisementDto $dto, string $expectedMessage): void
    {
        $catalogRepository = $this->createMock(CatalogReadRepositoryInterface::class);
        $catalogRepository->method('findById')->willReturn(new CatalogNode(10, 'Electronics', null));

        $advertisementRepository = $this->createMock(AdvertisementRepositoryInterface::class);
        $advertisementRepository->expects($this->never())->method('save');

        $service = new AdvertisementService($advertisementRepository, $catalogRepository);

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage($expectedMessage);

        $service->create($dto);
    }

    public function test_create_throws_when_catalog_does_not_exist(): void
    {
        $catalogRepository = $this->createMock(CatalogReadRepositoryInterface::class);
        $catalogRepository->expects($this->once())
            ->method('findById')
            ->with(10)
            ->willReturn(null);

        $advertisementRepository = $this->createMock(AdvertisementRepositoryInterface::class);
        $advertisementRepository->expects($this->never())->method('save');

        $service = new AdvertisementService($advertisementRepository, $catalogRepository);

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Catalog does not exist.');

        $service->create(new CreateAdvertisementDto(
            userId: 1,
            catalogId: 10,
            title: 'Title',
            description: 'Description',
            photos: ['/img/1.jpg'],
        ));
    }

    /**
     * @return array<string, array{0: CreateAdvertisementDto, 1: string}>
     */
    public static function invalidCreateDtoProvider(): array
    {
        return [
            'invalid user id' => [
                new CreateAdvertisementDto(0, 10, 'Title', 'Description', ['/img/1.jpg']),
                'User id must be a positive integer.',
            ],
            'invalid catalog id' => [
                new CreateAdvertisementDto(1, 0, 'Title', 'Description', ['/img/1.jpg']),
                'Catalog id must be a positive integer.',
            ],
            'empty title' => [
                new CreateAdvertisementDto(1, 10, '  ', 'Description', ['/img/1.jpg']),
                'Title is required.',
            ],
            'empty description' => [
                new CreateAdvertisementDto(1, 10, 'Title', '  ', ['/img/1.jpg']),
                'Description is required.',
            ],
            'empty photos list' => [
                new CreateAdvertisementDto(1, 10, 'Title', 'Description', []),
                'At least one photo is required.',
            ],
            'empty photo path' => [
                new CreateAdvertisementDto(1, 10, 'Title', 'Description', ['   ']),
                'Photo path must not be empty.',
            ],
        ];
    }
}
