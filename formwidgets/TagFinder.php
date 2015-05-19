<?php namespace Macrobit\FoodCatalog\FormWidgets;

use Backend\Classes\FormWidgetBase;
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
        $this->addJs('vendor/magicsuggest/magicsuggest-min.js', 'Macrobit.FoodCatalog');
        $this->addCss('css/tagfinder.css', 'Macrobit.FoodCatalog');
        $this->addJs('js/tagfinder.js', 'Macrobit.FoodCatalog');
    }

    /**
     * {@inheritDoc}
     */
    public function getSaveValue($value)
    {
        $tags = explode(',', $value);
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

    public function onFetchTags()
    {
        return json_encode($this->model->getTagOptions()->lists('id', 'name'), JSON_UNESCAPED_UNICODE);
    }

}
