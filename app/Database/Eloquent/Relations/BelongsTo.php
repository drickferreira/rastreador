<?php
declare(strict_types=1);

namespace App\Database\Eloquent\Relations;

use Illuminate\Database\Eloquent\Relations\BelongsTo as EloquentBelongsTo;

class BelongsTo extends EloquentBelongsTo
{
    /**
     * Gather the keys from an array of related models.
     *
     * @param  array  $models
     * @return array
     */
    protected function getEagerModelKeys(array $models)
    {
        $keys = [];

        // First we need to gather all of the keys from the parent models so we know what
        // to query for via the eager loading query. We will add them to an array then
        // execute a "where in" statement to gather up all of those related records.
        foreach ($models as $model) {
            if (! is_null($value = $model->{$this->foreignKey})) {
                $keys[] = $value;
            }
        }

        // If there are no keys that were not null we will just return an array with 0 in
        // it so the query doesn't fail, but will not return any results, which should
        // be what this developer is expecting in a case where this happens to them.
        if (count($keys) == 0) {
            return ['00000000-0000-0000-0000-000000000000'];
        }

        return array_values(array_unique($keys));
    }
}
