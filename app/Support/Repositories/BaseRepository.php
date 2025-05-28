<?php

namespace App\Support\Repositories;

use Illuminate\Database\Eloquent\Builder as EloquentQueryBuilder;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Pagination\AbstractPaginator as Paginator;

abstract class BaseRepository
{
    /** @var string */
    protected string $modelClass;

    /**
     * @return EloquentQueryBuilder|QueryBuilder
     */
    protected function newQuery() : EloquentQueryBuilder|QueryBuilder
    {
        return app($this->modelClass)->newQuery();
    }


    /**
     * @param EloquentQueryBuilder|QueryBuilder $query
     * @param int                               $take
     * @param bool                              $paginate
     *
     * @return EloquentCollection|Paginator
     */
    protected function doQuery(EloquentQueryBuilder|QueryBuilder|null $query = null, bool $paginate = false, int $take = 15, int $page = 1) : EloquentCollection|Paginator
    {
        if (is_null($query)) {
            $query = $this->newQuery();
        }

        if (true == $paginate) {
            return $query->paginate($take, ['*'], 'page', $page);
        }

        if ($take > 0 || false !== $take) {
            $query->take($take);
        }

        return $query->get();
    }

    /**
     * Returns all records.
     * If $take is false then brings all records
     * If $paginate is true returns Paginator instance.
     *
     * @param int  $take
     * @param bool $paginate
     *
     * @return EloquentCollection|Paginator
     */
    public function getAll(bool $paginate = false, int $take = 15, int $page = 1)
    {
        return $this->doQuery(null, $paginate, $take, $page);
    }

    /**
     * @param string      $column
     * @param string|null $key
     *
     * @return \Illuminate\Support\Collection
     */
    public function pluck(string $column, string|null $key = null)
    {
        return $this->newQuery()->pluck($column, $key);
    }

    /**
     * Retrieves a record by his id
     * If fail is true $ fires ModelNotFoundException.
     *
     * @param int  $id
     * @param bool $fail
     *
     * @return Model
     */
    public function findByID(int $id, bool $fail = true)
    {
        if ($fail) {
            return $this->newQuery()->findOrFail($id);
        }

        return $this->newQuery()->find($id);
    }

}
