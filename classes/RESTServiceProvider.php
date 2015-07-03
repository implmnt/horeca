<?php namespace Macrobit\Horeca\Classes;

use Route;
use Input;
use Auth;
use ShahiemSeymor\Roles\Models\UserGroup;

/**
 * REST Service Provider
 *
 * Usage: RESTServiceProvider::instance()->initialize([
 *     '\Macrobit\Horeca\Models\ModelName'
 * ]);
 *
 * Default prefix is 'rest'
 *
 */

class RESTServiceProvider
{
    use \October\Rain\Support\Traits\Singleton;

    protected $properties = [];

    protected $prefix = null;

    protected $defaultMethodList = ['GET', 'POST', 'PUT', 'DELETE'];

    public function initialize($properties = [], $prefix = 'rest')
    {
        $this->prefix = $prefix;
        $this->properties = $properties;
        $this->makeGroupRoute();
    }

    private function makeGroupRoute()
    {
        Route::group(['prefix' => $this->prefix], function() 
        {
            $this->provideAll();
        });
    }

    private function hasAccess($class)
    {
        $instance = new $class;
        return $instance->requiredPermissions ? 
            UserGroup::can($instance->requiredPermissions) : true;
    }

    private function provideAll()
    {
        foreach ($this->properties as $item) {
            $this->provide($item);
        }
    }

    private function provide($item)
    {
        foreach ($this->getMethods($item) as $method) {
            $this->makeRoute($this->getPath($item), 
                $this->getClass($item), $method);
        }
    }

    private function makeRoute($path, $class, $method)
    {
        switch ($method) {
            case 'GET':
                $this->makeGetRoute($path, $class);
                break;
            case 'POST':
                $this->makePostRoute($path, $class);
                break;
            case 'PUT':
                $this->makePutRoute($path, $class);
                break;
            case 'DELETE':
                $this->makeDeleteRoute($path, $class);
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
    private function makeGetRoute($path, $class)
    {
        Route::get($path, function() use ($class)
        {
            if (!$this->hasAccess($class)) {
                return 'forbidden';
            }

            $query = $class::query();

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

        Route::get($path . '/{id}', function($id) use ($class)
        {
            return $class::find($id);
        });
    }

    private function makePostRoute($path, $class)
    {
        Route::post($path, function() use ($class)
        {
            if (!$this->hasAccess($class)) {
                return 'forbidden';
            }

            $data = Input::all();
            $model = new $class($data);
            $model->save();
            
            return $model;
        });
    }

    private function makePutRoute($path, $class)
    {
        Route::put($path, function() use ($class)
        {
            if (!$this->hasAccess($class)) {
                return 'forbidden';
            }

            $data = Input::all();
            $model = $class::find($data['id']);
            $model->fill($data);
            $model->save();

            return $model;
        });
    }

    private function makeDeleteRoute($path, $class)
    {
        Route::delete($path . '/{id}', function($id) use ($class)
        {
            if (!$this->hasAccess($class)) {
                return 'forbidden';
            }

            $model = $class::find($id);
            $model->delete();

            return $model;
        });
    }

    private function getPath($propertyItem)
    {
        return $propertyItem[0];
    }    

    private function getClass($propertyItem)
    {
        return $propertyItem[1];
    }    


    private function getMethods($propertyItem)
    {
        return sizeof($propertyItem) > 2 ? 
            $propertyItem[2] : $this->defaultMethodList;
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

}