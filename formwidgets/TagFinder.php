<?php namespace Macrobit\FoodCatalog\FormWidgets;

use Backend\Classes\FormWidgetBase;
use Macrobit\FoodCatalog\Models\Tag as TagModel;
use Request;

/**
 * TagFinder Form Widget
 */
class TagFinder extends FormWidgetBase
{

    /**
     * {@inheritDoc}
     */
    protected $defaultAlias = 'macrobit_foodcatalog_tag_finder';

    /**
     * {@inheritDoc}
     */
    public function init()
    {
    }

    public function widgetDetails()
    {
        return [
            'name'        => 'Tag Finder',
            'description' => 'Renders a tag finder field.'
        ];
    }


    /**
     * {@inheritDoc}
     */
    public function render()
    {
        $this->prepareVars();
        return $this->makePartial('tagfinder');
    }

    /**
     * Prepares the form widget view data
     */
    public function prepareVars()
    {
        $this->vars['name'] = $this->formField->getName();
        $this->vars['value'] = json_encode($this->getLoadValue());
        $this->vars['model'] = $this->model;
    }

    /**
     * {@inheritDoc}
     */
    public function loadAssets()
    {
        $this->addCss('vendor/magicsuggest/magicsuggest-min.css', 'Macrobit.FoodCatalog');
        $this->addCss('css/tagfinder.css', 'Macrobit.FoodCatalog');
        $this->addJs('vendor/magicsuggest/magicsuggest-min.js', 'Macrobit.FoodCatalog');
        $this->addJs('js/tagfinder.js', 'Macrobit.FoodCatalog');
    }

    /**
     * {@inheritDoc}
     */
    public function getSaveValue($value)
    {
        $tags = explode(',', $value);
        // $ids = [];
        // foreach ($tags as $key => $value) {
        //     $tagId = TagModel::where('name', '=', $value)->get()[0]->id;
        //     array_push($ids, $tagId);
        // }
        return $tags;
    }

    public function printValue($value)
    {
        if (is_array($value)) {
            if (sizeof($value) > 1) {
                return implode(', ', $value);
            } else {
                return $value[0];
            }
        } else {
            return $value;
        }
    }

    // public function onTagNamesByIds($ids)
    // {
    //     $ids = Request::input('ids');
    //     $ids = explode(',', $ids);
    //     return json_encode(TagModel::find($ids)->lists('id', 'name'), JSON_UNESCAPED_UNICODE);
    // }

    // public function onTagsByQuery()
    // {
    //     $searchPattern = Request::input('query');

    //     $tags = TagModel::where('name', 'like', $searchPattern . '%')->lists('id', 'name');

    //     return json_encode($tags, JSON_UNESCAPED_UNICODE);
    // }

    public function onFetchTags()
    {
        return json_encode(TagModel::all()->lists('id', 'name'), JSON_UNESCAPED_UNICODE);
    }

}
