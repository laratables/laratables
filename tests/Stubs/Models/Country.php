<?php

namespace Freshbitsweb\Laratables\Tests\Stubs\Models;

use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    /**
     * The region that the country belongs to.
     */
    public function region()
    {
        return $this->belongsTo(Region::class);
    }

    /**
     * The users that belong to this country.
     */
    public function users()
    {
        return $this->hasMany(User::class);
    }
}
