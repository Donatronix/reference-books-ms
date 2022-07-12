<?php

namespace App\Services;

abstract class BaseService
{
    public $model;

    public function create(array $data)
    {
        return $this->model::create($data);
    }

    public function get()
    {
        return $this->model::get();
    }

    public function read($id)
    {
        return $this->model::find($id);
    }

    public function paginate($limit = 10)
    {
        return $this->model::latest()->paginate($limit);
    }

    public function update(array $data, $id)
    {
        $model = $this->read($id);
        if (!$model) {
           throw new \Exception("Model not found", 404);
        }

        $model->fill($data);
        $model->save();

        return $model;
    }

    public function delete($id)
    {
        $model = $this->read($id);
        if (!$model) {
           throw new \Exception("Model not found", 404);
        }
        $model->delete();

        return $model;
    }
}
