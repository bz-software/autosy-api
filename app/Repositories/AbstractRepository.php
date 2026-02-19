<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;

abstract class AbstractRepository
{
    protected Model $model;

    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    public function create(array $data): Model
    {
        return $this->model->create($data);
    }

    public function update(int $id, array $data): Model
    {
        $record = $this->findOrFail($id);
        $record->update($data);

        return $record;
    }

    public function findById(int $id): ?Model
    {
        return $this->baseQuery()->find($id);
    }

    public function findOrFail(int $id): Model
    {
        return $this->baseQuery()->findOrFail($id);
    }

    public function getAll()
    {
        return $this->baseQuery()->get();
    }

    public function delete(int $id): void
    {
        $record = $this->findOrFail($id);

        if ($this->hasDeletedColumn()) {
            $record->update(['deleted' => 1]);
        } else {
            $record->delete();
        }
    }

    protected function baseQuery()
    {
        if ($this->hasDeletedColumn()) {
            return $this->model->where('deleted', 0);
        }

        return $this->model->newQuery();
    }

    protected function hasDeletedColumn(): bool
    {
        return Schema::hasColumn($this->model->getTable(), 'deleted');
    }
}
