<?php

namespace Freshbitsweb\Laratables\Tests\Stubs\Models;

class UserNestedSearchable extends User
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'users';

    /**
     * Additional searchable columns to be used for datatables.
     * Includes nested relation for searching.
     *
     * @return array
     */
    public static function laratablesSearchableColumns()
    {
        return ['country.region.name'];
    }
}
