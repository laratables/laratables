<?php

namespace Freshbitsweb\Laratables\Tests\Stubs\Models;

use Illuminate\Database\Eloquent\Model;

class Region extends Model
{
    /**
     * The countries that belong to this region.
     */
    public function countries()
    {
        return $this->hasMany(Country::class);
    }
}
