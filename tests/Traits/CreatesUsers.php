<?php

namespace Freshbitsweb\Laratables\Tests\Traits;

use Freshbitsweb\Laratables\Tests\Stubs\Models\Country;
use Freshbitsweb\Laratables\Tests\Stubs\Models\Region;
use Freshbitsweb\Laratables\Tests\Stubs\Models\User;

trait CreatesUsers
{
    /**
     * Seeds user(s) in the database.
     *
     * @param int Number of users to be created
     * @param array parameters to override
     * @return mixed
     */
    protected function createUsers($count = 1, $parameters = [])
    {
        factory(Country::class)->create();

        return factory(User::class, $count)->create($parameters);
    }

    /**
     * Seeds user(s) in the database with nested region relationship.
     *
     * @param int $count Number of users to be created
     * @param array $parameters Parameters to override
     * @param string|null $regionName Optional region name
     * @return mixed
     */
    protected function createUsersWithRegion($count = 1, $parameters = [], $regionName = null)
    {
        $regionAttributes = $regionName ? ['name' => $regionName] : [];
        $region = factory(Region::class)->create($regionAttributes);

        factory(Country::class)->create(['region_id' => $region->id]);

        return factory(User::class, $count)->create($parameters);
    }
}
