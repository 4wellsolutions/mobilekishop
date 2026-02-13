<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Http\Request;
use App\Http\Middleware\CachePage;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Auth;
use Closure;

class CacheExplosionTest extends TestCase
{
    public function test_skips_cache_for_search_query()
    {
        $middleware = new CachePage();
        $request = Request::create('/search', 'GET', ['query' => 'foo']);

        $next = function ($req) {
            return response('content');
        };

        // We expect NO Cache::get or Cache::put interactions if it returns early.
        // However, since we can't easily mock facades in a standalone script without full app boot,
        // we can check if the response headers contain 'X-Page-Cache'.
        // If it skips, it calls $next directly, which returns a response object.
        // The middleware only adds headers if it hits or stores.

        // Wait, the middleware ADDS 'X-Page-Cache: MISS' if it STORES.
        // So if we skip, we should NOT see 'X-Page-Cache'.

        Cache::shouldReceive('get')->never();
        Cache::shouldReceive('put')->never();

        $response = $middleware->handle($request, $next);

        // If the middleware skipped matching, it wouldn't try to cache.
        $this->assertFalse($response->headers->has('X-Page-Cache'), 'Search request should skip cache logic entirely');
    }

    public function test_normalizes_tracking_params()
    {
        // We need to access the private cacheKey method or inspect the key used in Cache::get

        $middleware = new CachePage();

        // Request 1: Clean URL
        $request1 = Request::create('/', 'GET');

        // Request 2: URL with tracking
        $request2 = Request::create('/', 'GET', ['utm_source' => 'facebook', 'fbclid' => '123']);

        // Use reflection to call private cacheKey method
        $reflection = new \ReflectionClass(CachePage::class);
        $method = $reflection->getMethod('cacheKey');
        $method->setAccessible(true);

        $key1 = $method->invokeArgs($middleware, [$request1]);
        $key2 = $method->invokeArgs($middleware, [$request2]);

        $this->assertEquals($key1, $key2, 'Tracking parameters should be ignored in cache key');
    }

    public function test_sorts_query_params()
    {
        $middleware = new CachePage();

        // Request 1: a=1, b=2
        $request1 = Request::create('/', 'GET', ['a' => '1', 'b' => '2']);

        // Request 2: b=2, a=1
        $request2 = Request::create('/', 'GET', ['b' => '2', 'a' => '1']);

        $reflection = new \ReflectionClass(CachePage::class);
        $method = $reflection->getMethod('cacheKey');
        $method->setAccessible(true);

        $key1 = $method->invokeArgs($middleware, [$request1]);
        $key2 = $method->invokeArgs($middleware, [$request2]);

        $this->assertEquals($key1, $key2, 'Query parameters should be sorted in cache key');
    }
}
