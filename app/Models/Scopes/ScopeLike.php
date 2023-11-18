<?php

namespace App\Models\Scopes;

use Illuminate\Database\Eloquent\Builder;

trait ScopeLike
{
    /**
     * Get first image.
     *
     * @param  Builder  $query Query builder laravel
     * @param  string  $column Column search
     * @param  string  $value Value search
     */
    public function scopeWhereLike($query, string $column, string $value): Builder
    {
        if (config('database.default') == 'pgsql') {
            $query->where($column, 'ilike', "%$value%");
        } else {
            $query->where($column, 'like', "%$value%");
        }

        return $query;
    }

    /**
     * Get first image.
     *
     * @param  Builder  $query Query builder laravel
     * @param  string  $column Column search
     * @param  string  $value Value search
     */
    public function scopeOrWhereLike($query, string $column, string $value): Builder
    {
        if (config('database.default') == 'pgsql') {
            $query->orWhere($column, 'ilike', "%$value%");
        } else {
            $query->orWhere($column, 'like', "%$value%");
        }

        return $query;
    }

    /**
     * Get first image.
     *
     * @param  Builder  $query Query builder laravel
     * @param  string  $column Column search
     * @param  string  $value Value search
     */
    public function scopeWhereNotLike($query, string $column, string $value): Builder
    {
        if (config('database.default') == 'pgsql') {
            $query->where($column, 'Not ilike', "%$value%");
        } else {
            $query->where($column, 'Not like', "%$value%");
        }

        return $query;
    }

    /**
     * Get first image.
     *
     * @param  Builder  $query Query builder laravel
     * @param  string  $column Column search
     * @param  string  $value Value search
     */
    public function scopeOrWhereNotLike($query, string $column, string $value): Builder
    {
        if (config('database.default') == 'pgsql') {
            $query->orWhere($column, 'Not ilike', "%$value%");
        } else {
            $query->orWhere($column, 'Not like', "%$value%");
        }

        return $query;
    }
}
