<?php

declare(strict_types=1);

namespace ToolMountain\LocalizedRoutes\Tests\Feature\Macros\Route;

use Illuminate\Support\Facades\Route;
use PHPUnit\Framework\Attributes\Test;
use ToolMountain\LocalizedRoutes\Tests\TestCase;

final class IsLocalizedMacroTest extends TestCase
{
    #[Test]
    public function it_checks_if_the_current_route_is_localized(): void
    {
        $this->withoutExceptionHandling();
        $this->setSupportedLocales(['en', 'nl']);

        Route::localized(function () {
            Route::get('localized-route', fn () => Route::isLocalized() ? 'true' : 'false')->middleware(['web']);
        });

        Route::get('non-localized-route', fn () => Route::isLocalized() ? 'true' : 'false')->middleware(['web']);

        $response = $this->call('GET', '/en/localized-route');
        $response->assertOk();
        $this->assertEquals('true', $response->original);

        $response = $this->call('GET', '/nl/localized-route');
        $response->assertOk();
        $this->assertEquals('true', $response->original);

        $response = $this->call('GET', '/non-localized-route');
        $response->assertOk();
        $this->assertEquals('false', $response->original);
    }

    #[Test]
    public function it_checks_if_the_current_route_has_a_name_with_any_locale_prefix(): void
    {
        $this->withoutExceptionHandling();
        $this->setSupportedLocales(['en', 'nl']);

        Route::localized(function () {
            Route::get('route-one', fn () => Route::isLocalized('route-one') ? 'true' : 'false')->name('route-one')->middleware(['web']);

            Route::get('route-two', fn () => Route::isLocalized('route-one') ? 'true' : 'false')->name('route-two')->middleware(['web']);
        });

        $response = $this->call('GET', '/en/route-one');
        $response->assertOk();
        $this->assertEquals('true', $response->original);

        $response = $this->call('GET', '/nl/route-one');
        $response->assertOk();
        $this->assertEquals('true', $response->original);

        $response = $this->call('GET', '/en/route-two');
        $response->assertOk();
        $this->assertEquals('false', $response->original);

        $response = $this->call('GET', '/nl/route-two');
        $response->assertOk();
        $this->assertEquals('false', $response->original);
    }

    #[Test]
    public function it_checks_if_the_current_route_has_a_name_with_a_specific_locale_prefix(): void
    {
        $this->withoutExceptionHandling();
        $this->setSupportedLocales(['en', 'nl']);

        Route::localized(function () {
            Route::get('route-one', fn () => Route::isLocalized('route-one', 'en') ? 'true' : 'false')->name('route-one')->middleware(['web']);
        });

        $response = $this->call('GET', '/en/route-one');
        $response->assertOk();
        $this->assertEquals('true', $response->original);

        $response = $this->call('GET', '/nl/route-one');
        $response->assertOk();
        $this->assertEquals('false', $response->original);
    }

    #[Test]
    public function it_checks_if_the_current_route_has_a_name_that_is_in_an_array_of_names_with_any_locale_prefix(): void
    {
        $this->withoutExceptionHandling();
        $this->setSupportedLocales(['en', 'nl']);

        Route::localized(function () {
            Route::get('route-one', fn () => Route::isLocalized(['route-one', 'route-two']) ? 'true' : 'false')->name('route-one')->middleware(['web']);

            Route::get('route-two', fn () => Route::isLocalized(['route-one', 'route-two']) ? 'true' : 'false')->name('route-two')->middleware(['web']);

            Route::get('route-three', fn () => Route::isLocalized(['route-one', 'route-two']) ? 'true' : 'false')->name('route-three')->middleware(['web']);
        });

        $response = $this->call('GET', '/en/route-one');
        $response->assertOk();
        $this->assertEquals('true', $response->original);

        $response = $this->call('GET', '/nl/route-one');
        $response->assertOk();
        $this->assertEquals('true', $response->original);

        $response = $this->call('GET', '/en/route-two');
        $response->assertOk();
        $this->assertEquals('true', $response->original);

        $response = $this->call('GET', '/nl/route-two');
        $response->assertOk();
        $this->assertEquals('true', $response->original);

        $response = $this->call('GET', '/en/route-three');
        $response->assertOk();
        $this->assertEquals('false', $response->original);

        $response = $this->call('GET', '/nl/route-three');
        $response->assertOk();
        $this->assertEquals('false', $response->original);
    }

    #[Test]
    public function it_checks_if_the_current_route_has_a_name_that_is_in_an_array_of_names_with_a_specific_locale_prefix(): void
    {
        $this->withoutExceptionHandling();
        $this->setSupportedLocales(['en', 'nl']);

        Route::localized(function () {
            Route::get('route-one', fn () => Route::isLocalized(['route-one', 'route-two'], 'en') ? 'true' : 'false')->name('route-one')->middleware(['web']);

            Route::get('route-two', fn () => Route::isLocalized(['route-one', 'route-two'], 'en') ? 'true' : 'false')->name('route-two')->middleware(['web']);

            Route::get('route-three', fn () => Route::isLocalized(['route-one', 'route-two'], 'en') ? 'true' : 'false')->name('route-three')->middleware(['web']);
        });

        $response = $this->call('GET', '/en/route-one');
        $response->assertOk();
        $this->assertEquals('true', $response->original);

        $response = $this->call('GET', '/nl/route-one');
        $response->assertOk();
        $this->assertEquals('false', $response->original);

        $response = $this->call('GET', '/en/route-two');
        $response->assertOk();
        $this->assertEquals('true', $response->original);

        $response = $this->call('GET', '/nl/route-two');
        $response->assertOk();
        $this->assertEquals('false', $response->original);

        $response = $this->call('GET', '/en/route-three');
        $response->assertOk();
        $this->assertEquals('false', $response->original);

        $response = $this->call('GET', '/nl/route-three');
        $response->assertOk();
        $this->assertEquals('false', $response->original);
    }

    #[Test]
    public function it_checks_if_the_current_route_has_a_name_with_a_locale_prefix_in_an_array(): void
    {
        $this->withoutExceptionHandling();
        $this->setSupportedLocales(['en', 'nl', 'fr']);

        Route::localized(function () {
            Route::get('route-one', fn () => Route::isLocalized('route-one', ['en', 'nl']) ? 'true' : 'false')->name('route-one')->middleware(['web']);

            Route::get('route-two', fn () => Route::isLocalized('route-one', ['en', 'nl']) ? 'true' : 'false')->name('route-two')->middleware(['web']);
        });

        $response = $this->call('GET', '/en/route-one');
        $response->assertOk();
        $this->assertEquals('true', $response->original);

        $response = $this->call('GET', '/nl/route-one');
        $response->assertOk();
        $this->assertEquals('true', $response->original);

        $response = $this->call('GET', '/fr/route-one');
        $response->assertOk();
        $this->assertEquals('false', $response->original);

        $response = $this->call('GET', '/en/route-two');
        $response->assertOk();
        $this->assertEquals('false', $response->original);

        $response = $this->call('GET', '/nl/route-two');
        $response->assertOk();
        $this->assertEquals('false', $response->original);

        $response = $this->call('GET', '/fr/route-two');
        $response->assertOk();
        $this->assertEquals('false', $response->original);
    }
}
