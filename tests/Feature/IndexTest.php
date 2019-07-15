<?php

declare(strict_types = 1);

namespace Protoqol\Prequel\Tests\Feature;

use Protoqol\Prequel\Tests\TestCase;

/**
 * Class IndexTest
 * @package Protoqol\Prequel\Tests\Feature
 */
class IndexTest extends TestCase
{

    public function testIndexIsErrorIfInvalidDatabaseConnection(): void
    {
        // force a "known" state that shouldn't exist, so we can insure an error
        config(['database.default' => 'testing-prequel-error-connection']);
        config(['database.connections.testing-prequel-error-connection.driver' => 'mysql']);

        $response = $this->get(route('prequel.index'));

        $response->assertStatus(200); // 200, even though it's showing an error
        $response->assertViewIs('Prequel::error');
        $response->assertSeeText('Error in Prequel');
        $response->assertSeeText('Could not create a valid database connection.');
    }

    public function testIndexIsDeniedWhenPrequelIsDisabled(): void
    {
        config(['prequel.enabled' => false]);

        $response = $this->get(route('prequel.index'));
        $response->assertStatus(200); // 200, even though it's showing an error
        $response->assertViewIs('Prequel::error');
        $response->assertSeeText('Error in Prequel');
        $response->assertSeeText('Prequel has been disabled.');
    }

    // additional tests should be ran that indicate successful connections

    // I don't recommend testing with app environment is 'production' because that may cause other side effects
    // instead, in the future, create a method that can be mocked that returns whether we're in production or not.
}