<?php

namespace App\Modules\Base\Repositories;

use App\Modules\Base\Repositories\Contracts\RepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * @template T of Model
 * @implements RepositoryInterface<T>
 */
abstract class Repository implements RepositoryInterface
{
    public string $model;

    public function __construct(?string $modelClass = null)
    {
        $this->model = $modelClass ?: self::guessModelClass();
    }

    protected function getModel(): string
    {
        return $this->model;
    }

    private static function guessModelClass(): string
    {
        return preg_replace('/(.+)\\\\Repositories\\\\(.+)Repository$/m', '$1\Models\\\$2', static::class);
    }

    public function getOne($id): Model
    {
        $result = $this->getModel()::query()->findOrFail($id);
        return $result;
    }

    /** @inheritDoc */
    public function findOne($id): ?Model
    {
        return $this->getModel()::query()->find($id);
    }

    public function getOneBy(array $params): Model
    {
        return $this->getModel()::query()->where($params)->firstOrFail();
    }

    public function findOneBy(array $params): ?Model
    {
        return $this->getModel()::query()->where($params)->first();
    }

    public function getMany(array $ids, bool $preserveOrder = false): Collection
    {
        $models = $this->getModel()::query()->find($ids);

        return $preserveOrder ? $models->orderByArray($ids) : $models;
    }

    public function getAll(): Collection
    {
        return $this->getModel()::all();
    }

    public function findFirstWhere(...$params): ?Model
    {
        return $this->getModel()::query()->firstWhere(...$params);
    }
}
