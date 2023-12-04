<?php

namespace DTApi\Service;

use DTApi\Exceptions\ValidationException;
use DTApi\Repository\BaseRepository;
use Exception;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\Validator;

class BaseService
{
    /**
     * @var BaseRepository
     */
    protected $repository;

    /**
     * @param BaseRepository $repository
     */
    public function __construct(BaseRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @return Collection
     */
    public function all(): Collection
    {
        return $this->repository->all();
    }

    /**
     * @param int $id
     * @return Model|null
     */
    public function find(int $id): ?Model
    {
        return $this->repository->find($id);
    }

    /**
     * @param array $array
     * @return Builder
     */
    public function with(array $array): Builder
    {
        return $this->repository->with($array);
    }

    /**
     * @param integer $id
     * @return Model|null
     * @throws ModelNotFoundException
     */
    public function findOrFail(int $id): ?Model
    {
        return $this->repository->findOrFail($id);
    }

    /**
     * @param string $slug
     * @return Model|null
     * @throws ModelNotFoundException
     */
    public function findBySlug(string $slug): ?Model
    {
        return $this->repository->findBySlug($slug);
    }

    /**
     * @return Builder
     */
    public function query(): Builder
    {
        return $this->repository->query();
    }

    /**
     * @param array $attributes
     * @return Model
     */
    public function instance(array $attributes = []): Model
    {
        return $this->repository->instance($attributes);
    }

    /**
     * @param int|null $perPage
     * @return LengthAwarePaginator
     */
    public function paginate(?int $perPage = null): LengthAwarePaginator
    {
        return $this->repository->paginate($perPage);
    }

    /**
     * @param string $key
     * @param mixed $where
     * @return Builder
     */
    public function where(string $key, $where): Builder
    {
        return $this->repository->where($key, $where);
    }

    /**
     * @param array $data
     * @param null $rules
     * @param array $messages
     * @param array $customAttributes
     * @return Validator
     */
    public function validator(array $data = [], $rules = null, array $messages = [], array $customAttributes = []): Validator
    {
        return $this->repository->validator($data, $rules, $messages, $customAttributes);
    }

    /**
     * @param array $data
     * @param null $rules
     * @param array $messages
     * @param array $customAttributes
     * @return bool
     * @throws ValidationException
     */
    public function validate(array $data = [], $rules = null, array $messages = [], array $customAttributes = []): bool
    {
        return $this->repository->validate($data, $rules, $messages, $customAttributes);
    }

    /**
     * @param array $data
     * @return Model
     */
    public function create(array $data = []): Model
    {
        return $this->repository->create($data);
    }

    /**
     * @param integer $id
     * @param array $data
     * @return Model
     */
    public function update(int $id, array $data = []): Model
    {
        return $this->repository->update($id, $data);
    }

    /**
     * @param integer $id
     * @return Model
     * @throws Exception
     */
    public function delete(int $id): Model
    {
        return $this->repository->delete($id);
    }
}