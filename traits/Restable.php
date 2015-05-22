<?php namespace Macrobit\FoodCatalog\Traits;

use Route;
use Input;
use Response;

trait Restable
{
    public static function makeRestable()
    {   

        $PATH = self::$REST_PATH_NAME;

        Route::get($PATH, function()
        {
            $models = self::all();

            return self::response($models, 200);
        });

        Route::get($PATH . '/{id}', function($id)
        {
            $model = self::find($id);

            return self::response($model, 200);
        });

        Route::post($PATH, function()
        {
            $data = Input::all();
            $model = new self($data);
            $model->save();
            
            return self::response($model, 200);
        });

        Route::put($PATH, function()
        {
            $data = Input::all();
            $model = self::find($data['id']);
            $model->fill($data);
            $model->save();

            return self::response($model, 200);
        });

        Route::delete($PATH . '/{id}', function($id)
        {
            $model = self::find($id);
            $model->delete();

            return self::response($model, 200);
        });


    }

    public static function response($data, $status)
    {
        return Response::make(json_encode($data, JSON_UNESCAPED_UNICODE), 
            $status)->header('Content-Type', 'application/json');
    }

}