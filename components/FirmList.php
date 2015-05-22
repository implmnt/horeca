<?php namespace Macrobit\FoodCatalog\Components;

use Cms\Classes\ComponentBase;
use Macrobit\FoodCatalog\Models\Firm as FirmModel;

class FirmList extends ComponentBase
{

    public function componentDetails()
    {
        return [
            'name'        => 'FirmList Component',
            'description' => 'No description provided yet...'
        ];
    }

    public function defineProperties()
    {
        return [];
    }

    public function onRun()
    {
        $items = [];
        $firmModels = FirmModel::all();
        foreach ($firmModels as $firmModel) {
            $item = [
                'name' => $firmModel->name,
                'address' => $firmModel->address,
                'image' => $firmModel->image,
                'phone' => $firmModel->phone,
                'tags' => implode(', ', $firmModel->tags()->lists('name'))
            ];
            array_push($items, $item);
        }
        $this->page['items'] = $items;
    }

}