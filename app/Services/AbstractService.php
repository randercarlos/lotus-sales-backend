<?php

namespace App\Services;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

abstract class AbstractService
{
    protected $model;

    public function findAll(Request $request) {
        return $this->model->get();
    }
    public function find(int $id): Model {
        if (!$record = $this->model->find($id)) {
            throw new \Exception("Record with id $id not exists" );
        }

        return $record;
    }
    public function save(Request $request, $id = null): Model {

        if ($record = $this->model->find($id)) {
            if (!$record->update($request->all())) {
                throw new \Exception("Fail on update record with values: " . $request->all());
            }

            return $record;
        } else {
            $this->model->fill($request->all());
            if (!$this->model->save()) {
                throw new \Exception("Fail on create record with values: " . $request->all());
            }
        }

        return $this->model;
    }
    public function delete(int $id): bool {
        if (!$this->model->find($id)) {
            throw new \Exception("Record with id $id not exists" );
        }

        if (!$this->model->delete()) {
            throw new \Exception("Fail on delete record with id $id" );
        }
    }
}
