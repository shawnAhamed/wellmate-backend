<?php

namespace App\Repositories\Contracts;

use Illuminate\Support\Collection;

interface TagRepositoryInterface extends RepositoryInterface
{
    /**
     * @param  string[]  $names
     */
    public function findOrCreateMany(array $names): Collection;
}
