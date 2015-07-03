<?php namespace Macrobit\Horeca\Classes;

use Route;
use Response;
use Input;
use ShahiemSeymor\Roles\Models\UserGroup;

class RESTManager 
{
    use \October\Rain\Support\Traits\Singleton;

    protected $models = [];

    protected $prefix = 'rest';

    protected $groupRoute = null;

    public function initialize($classes = [], $prefix = null)
    {
        if ($prefix) {
            $this->prefix = $prefix;
        }
        
        foreach ($classes as $class) {
            array_push($this->models, new $class());
        }

        $this->groupRoute = Route::group(['prefix' => $this->prefix], function() 
        {
            $this->provide($this->models);
        });
    }

    private function provide($models = [])
    {
        foreach ($models as $model) {
            $config = $model->restConfig;
            $modelMethods = $config['methods'];
            foreach ($modelMethods as $method => $permissions) {
                $query = $model->newQuery()->with($config['relations']);
                $this->makeRoute($method, $config['path'], 
                    $query, $permissions);
            }
        }
    }

    private function makeRoute($method, $path, $query, $permissions)
    {
        switch ($method) {
            case 'GET':
                $this->makeGetRoute($path, $query, $permissions);
                break;
            case 'POST':
                $this->makePostRoute($path, $query, $permissions);
                break;
            case 'PUT':
                $this->makePutRoute($path, $query, $permissions);
                break;
            case 'DELETE':
                $this->makeDeleteRoute($path, $query, $permissions);
                break;
            default:
                break;
        }
    }

    /**
     * Creates GET operation
     *
     * GET all records:    ../PREFIX/MODEL_ID
     * With QUERY:         ../PREFIX/MODEL_ID?q={'field':'value'}
     *                     ../PREFIX/MODEL_ID?q={'field':{'$like':'%value%'}}
     *                     ../PREFIX/MODEL_ID?q={'field':{'$bt':100}}
     *                     ../PREFIX/MODEL_ID?q={'field':{'$bte':100}}
     *                     ../PREFIX/MODEL_ID?q={'field':{'$lt':100}}
     *                     ../PREFIX/MODEL_ID?q={'field':{'$lte':100}}
     * With PAGINATION:    ../PREFIX/MODEL_ID?psize=10&p=1
     * With SORT:          ../PREFIX/MODEL_ID?s={'$desc':'field'}
     *                     ../PREFIX/MDOEL_ID?s={'$asc':'field'}
     * GET records count:  ../PREFIX/MODEL_ID?c=true
     * GET record by ID:   ../PREFIX/MODEL_ID/RECORD_ID
     */
    private function makeGetRoute($path, $query, $permissions)
    {

        Route::get($path, function() use ($query, $permissions)
        {

            if (!$this->checkAccess($permissions)) {
                return $this->forbidden();
            }

            /**
             * Handle Query
             */
            if (Input::has('q')) {
                $queryData = json_decode(
                    Input::get('q'), JSON_UNESCAPED_UNICODE);
                $this->applyQuery($query, $queryData);
            }

            /**
             * Handle Count
             */
            if (Input::has('c')) {
                return $query->count();
            }

            /**
             * Handle Pagination
             */
            if (Input::has('psize') && Input::has('p')) {
                $this->applyPaginate($query, 
                    Input::get('psize'), Input::get('p'));
            }

            /**
             * Handle Sort
             */
            if (Input::has('s')) {
                $sortData = json_decode(
                    Input::get('s'), JSON_UNESCAPED_UNICODE);
                $this->applySort($query, $sortData);
            }

            return $query->get();
        });

        Route::get($path . '/{id}', function($id) use ($query, $permissions)
        {
            if (!$this->checkAccess($permissions)) {
                return $this->forbidden();
            }

            return $query->find($id);
        });
    }

    private function makePostRoute($path, $query, $permissions)
    {
        Route::post($path, function() use ($query, $permissions)
        {
            if (!$this->checkAccess($permissions)) {
                return $this->forbidden();
            }

            $data = Input::all();
            $model = $query->getModel();
            $model->fill($data);
            $model->save();
            
            return $model;
        });
    }

    private function makePutRoute($path, $query, $permissions)
    {
        Route::put($path, function() use ($query, $permissions)
        {
            if (!$this->checkAccess($permissions)) {
                return $this->forbidden();
            }

            $data = Input::all();
            $model = $query->find($data['id']);
            $model->fill($data);
            $model->save();

            return $model;
        });
    }

    private function makeDeleteRoute($path, $query, $permissions)
    {
        Route::delete($path . '/{id}', function($id) use ($query, $permissions)
        {
            if (!$this->checkAccess($permissions)) {
                return $this->forbidden();
            }

            $model = $query->find($id);
            $model->delete();

            return $model;
        });
    }

    private function applyQuery($query, $queryData)
    {
        foreach ($queryData as $field => $value) {
            $this->applyQueryFieldValue($query, $field, $value);
        }
    }

    private function applyQueryFieldValue($query, $field, $value)
    {
        if (is_array($value)) {
            $operator = key($value);
            $value = $value[$operator];
            switch ($operator) {
                case '$like':
                    $query->where($field, 'like', $value);
                    break;
                case '$lt':
                    $query->where($field, '<', $value);
                    break;
                case '$lte':
                    $query->where($field, '<=', $value);
                    break;
                case '$bt':
                    $query->where($field, '>', $value);
                    break;
                case '$bte':
                    $query->where($field, '>=', $value);
                    break;
                default:
                    break;
            }
        } else {
            $query->where($field, $value);
        }
    }

    private function applyPaginate($query, $pageSize, $page)
    {
        $query->paginate($pageSize, $page);
    }

    private function applySort($query, $field)
    {
        if (is_array($field)) {
            $order = key($field);
            $field = $field[$order];
            switch ($order) {
                case '$asc':
                    $query->orderBy($field);
                    break;
                case '$desc':
                    $query->orderBy($field, 'desc');
                default:
                    break;
            }
        } else {
            $query->sortBy($field);
        }
    }

    private function checkAccess($permissions)
    {
        if ($permissions[0] === '*') {
            return true;
        }
        return UserGroup::can($permissions);
    }

    private function forbidden() {
        return response(null, 403)
            ->header('Content-Type', 'application/json');
    }

}