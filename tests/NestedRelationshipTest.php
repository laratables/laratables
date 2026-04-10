<?php

namespace Freshbitsweb\Laratables\Tests;

use Illuminate\Support\Str;
use PHPUnit\Framework\Attributes\Test;

class NestedRelationshipTest extends TestCase
{
    #[Test]
    public function it_returns_nested_relationship_column_value()
    {
        $users = $this->createUsersWithRegion(1, [], 'Europe');

        $response = $this->json(
            'GET',
            '/datatables-nested-relation',
            $this->getDatatablesUrlParameters('', 10, [], ['country.region.name'])
        );

        $response->assertJson([
            'recordsTotal' => 1,
            'data' => [[
                '6' => 'Europe', // Nested relation value: country.region.name
            ]],
        ]);
    }

    #[Test]
    public function it_searches_nested_relationship_columns()
    {
        $regionName = Str::random(20);

        $users = $this->createUsersWithRegion(1, [], $regionName);

        $response = $this->json(
            'GET',
            '/datatables-nested-searchable',
            $this->getDatatablesUrlParameters($regionName)
        );

        $response->assertJson([
            'recordsTotal' => 1,
            'recordsFiltered' => 1,
            'data' => [[
                '0' => 1,
                '1' => $users->first()->name,
            ]],
        ]);
    }

    #[Test]
    public function it_does_not_find_nested_relationship_when_search_does_not_match()
    {
        $this->createUsersWithRegion(1, [], 'Europe');

        $response = $this->json(
            'GET',
            '/datatables-nested-searchable',
            $this->getDatatablesUrlParameters('NonExistentRegion12345')
        );

        $response->assertJson([
            'recordsTotal' => 1,
            'recordsFiltered' => 0,
            'data' => [],
        ]);
    }

    #[Test]
    public function it_maintains_backward_compatibility_with_single_level_relations()
    {
        $users = $this->createUsers();

        $response = $this->json('GET', '/datatables-simple', $this->getDatatablesUrlParameters());

        $response->assertJson([
            'recordsTotal' => 1,
            'data' => [[
                '0' => 1,
                '1' => $users->first()->name,
                '2' => $users->first()->email,
                '4' => $users->first()->country->name,
            ]],
        ]);
    }
}
