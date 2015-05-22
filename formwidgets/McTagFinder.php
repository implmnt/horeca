<?php namespace Macrobit\FoodCatalog\FormWidgets;

use Backend\Classes\FormWidgetBase;
use Request;

/**
 * mc-tagFinder Form Widget
 */
class McTagFinder extends FormWidgetBase
{

    /**
     * {@inheritDoc}
     */
    protected $defaultAlias = 'macrobit_foodcatalog_mc-tag_finder';

    /**
     * [$models description]
     * @var the name of function which allows to get models
     */
    public $models = null;

    /**
     * {@inheritDoc}
     */
    public function init()
    {
        $this->fillFromConfig([
            'models'
        ]);
    }

    /**
     * {@inheritDoc}
     */
    public function render()
    {
        $this->prepareVars();
        return $this->makePartial('mc-tagfinder');
    }

    /**
     * Prepares the form widget view data
     */
    public function prepareVars()
    {
        $this->vars['name'] = $this->formField->getName();
        $this->vars['value'] = json_encode($this->getLoadValue(), JSON_UNESCAPED_UNICODE);
        $this->vars['tags'] = json_encode($this->getTags(), JSON_UNESCAPED_UNICODE);
        $this->vars['model'] = $this->model;
    }

    /**
     * {@inheritDoc}
     */
    public function loadAssets()
    {
        $this->addCss('vendor/magicsuggest/magicsuggest-min.css', 'Macrobit.FoodCatalog');
        $this->addJs('vendor/magicsuggest/magicsuggest-min.js', 'Macrobit.FoodCatalog');
        $this->addCss('css/mc-tagfinder.css', 'Macrobit.FoodCatalog');
        $this->addJs('js/mc-tagfinder.js', 'Macrobit.FoodCatalog');
    }

    /**
     * {@inheritDoc}
     */
    public function getSaveValue($value)
    {
        return $value;
    }

    public function getTags()
    {
        $tagModels = $this->model->getTagOptions();
        $tags = [];
        foreach ($tagModels as $tag) {
            array_push($tags, [
                'id' => $tag->id,
                'value' => $tag->name
            ]);
        }
        return $tags;
    }

}
