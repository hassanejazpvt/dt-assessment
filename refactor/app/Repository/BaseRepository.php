<?php

namespace DTApi\Repository;

use DTApi\Exceptions\ValidationException;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\Validator;

class BaseRepository
{

    /**
     * @var Model
     */
    protected $model;

    /**
     * @var array
     */
    protected $validationRules = [];

    /**
     * @param Model $model
     */
    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    /**
     * @return array
     */
    public function validatorAttributeNames(): array
    {
        return [];
    }

    /**
     * @return Model
     */
    public function getModel(): Model
    {
        return $this->model;
    }

    /**
     * @return Collection
     */
    public function all(): Collection
    {
        return $this->model->all();
    }

    /**
     * @param integer $id
     * @return Model|null
     */
    public function find(int $id): ?Model
    {
        return $this->model->find($id);
    }

    /**
     * @param array $array
     * @return Builder
     */
    public function with(array $array): Builder
    {
        return $this->model->with($array);
    }

    /**
     * @param integer $id
     * @return Model|null
     * @throws ModelNotFoundException
     */
    public function findOrFail(int $id): ?Model
    {
        return $this->model->findOrFail($id);
    }

    /**
     * @param string $slug
     * @return Model|null
     */
    public function findBySlug(string $slug): ?Model
    {
        return $this->model->where('slug', $slug)->first();
    }

    /**
     * @return Builder
     */
    public function query(): Builder
    {
        return $this->model->query();
    }

    /**
     * @param array $attributes
     * @return Model
     */
    public function instance(array $attributes = []): Model
    {
        $model = $this->model;
        return new $model($attributes);
    }

    /**
     * @param int|null $perPage
     * @return Validator
     */
    public function paginate(?int $perPage = null): Validator
    {
        return $this->model->paginate($perPage);
    }

    /**
     * @param string $key
     * @param mixed $where
     * @return Builder
     */
    public function where(string $key, $where): Builder
    {
        return $this->model->where($key, $where);
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
        if (is_null($rules)) {
            $rules = $this->validationRules;
        }

        return Validator::make($data, $rules, $messages, $customAttributes);
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
        $validator = $this->validator($data, $rules, $messages, $customAttributes);
        return $this->_validate($validator);
    }

    /**
     * @param array $data
     * @return Model
     */
    public function create(array $data = []): Model
    {
        return $this->model->create($data);
    }

    /**
     * @param integer $id
     * @param array $data
     * @return Model
     */
    public function update(int $id, array $data = []): Model
    {
        $instance = $this->findOrFail($id);
        $instance->update($data);
        return $instance;
    }

    /**
     * @param integer $id
     * @return Model
     * @throws Exception
     */
    public function delete(int $id): Model
    {
        $model = $this->findOrFail($id);
        $model->delete();
        return $model;
    }

    /**
     * @param Validator $validator
     * @return bool
     * @throws ValidationException
     */
    protected function _validate(Validator $validator): bool
    {
        if (!empty($attributeNames = $this->validatorAttributeNames())) {
            $validator->setAttributeNames($attributeNames);
        }

        if ($validator->fails()) {
            return false;
            throw (new ValidationException)->setValidator($validator);
        }

        return true;
    }
}