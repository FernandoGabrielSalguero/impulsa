<?php

namespace Tests\Unit;

use App\PublicEmbed\Services\PublicMediaFileService;
use App\Services\ApiBlog\ApiBlogPostStorageService;
use App\Services\ApiProduct\ApiProductStorageService;
use Illuminate\Validation\ValidationException;
use Mockery;
use Tests\TestCase;

class PublicMediaFileServiceTest extends TestCase
{
    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function test_rejects_path_traversal(): void
    {
        $service = new PublicMediaFileService(
            Mockery::mock(ApiBlogPostStorageService::class),
            Mockery::mock(ApiProductStorageService::class),
        );

        $this->expectException(ValidationException::class);
        $service->resolve('../secret.txt');
    }

    public function test_rejects_unallowed_prefix(): void
    {
        $service = new PublicMediaFileService(
            Mockery::mock(ApiBlogPostStorageService::class),
            Mockery::mock(ApiProductStorageService::class),
        );

        $this->expectException(ValidationException::class);
        $service->resolve('private/file.png');
    }
}
