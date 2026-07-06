<?php

namespace Tests\Unit;

use App\Services\PublicApi\PublicMediaUrlBuilder;
use Illuminate\Support\Facades\Config;
use Tests\TestCase;

class PublicMediaUrlBuilderTest extends TestCase
{
    public function test_builds_url_from_stored_path_with_public_storage_base_url(): void
    {
        Config::set('impulsa.public_storage_base_url', 'https://impulsagroup.com/storage');
        Config::set('impulsa.public_api_base_url', 'https://impulsagroup.com');

        $builder = new PublicMediaUrlBuilder();

        $this->assertSame(
            'https://impulsagroup.com/storage/API_Blog/blog-cover_test.png',
            $builder->url('API_Blog/blog-cover_test.png'),
        );
    }

    public function test_falls_back_to_public_api_base_url_when_storage_base_is_empty(): void
    {
        Config::set('impulsa.public_storage_base_url', '');
        Config::set('impulsa.public_api_base_url', 'https://impulsagroup.com');

        $builder = new PublicMediaUrlBuilder();

        $this->assertSame(
            'https://impulsagroup.com/storage/API_Product/product_main_test.jpg',
            $builder->url('API_Product/product_main_test.jpg'),
        );
    }

    public function test_returns_null_for_empty_path(): void
    {
        $builder = new PublicMediaUrlBuilder();

        $this->assertNull($builder->url(null));
        $this->assertNull($builder->url(''));
    }
}
