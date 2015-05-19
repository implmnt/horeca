<?php namespace Macrobit\FoodCatalog\FormWidgets;

use Backend\Classes\FormWidgetBase;
use ApplicationException;

/**
 * mc-rangeSlider Form Widget
 */
class McRangeSlider extends FormWidgetBase
{

    /**
     * {@inheritDoc}
     */
    protected $defaultAlias = 'macrobit_foodcatalog_mc-range_slider';

    /**
     * {@inheritDoc}
     */
    public function init()
    {
    }

    /**
     * {@inheritDoc}
     */
    public function render()
    {
        $this->prepareVars();
        return $this->makePartial('mc-rangeslider');
    }

    /**
     * Prepares the form widget view data
     */
    public function prepareVars()
    {
        $this->vars['name'] = $this->formField->getName();
        $this->vars['value'] = $this->getLoadValue();
        $this->vars['model'] = $this->model;
    }

    /**
     * {@inheritDoc}
     */
    public function loadAssets()
    {
        $this->addJs('vendor/liblink/jquery.liblink.js', 'Macrobit.FoodCatalog');
        $this->addCss('vendor/nouislider/jquery.nouislider.css', 'Macrobit.FoodCatalog');
        $this->addJs('vendor/nouislider/jquery.nouislider.js', 'Macrobit.FoodCatalog');
        $this->addCss('css/mc-rangeslider.css', 'Macrobit.FoodCatalog');
        $this->addJs('js/mc-rangeslider.js', 'Macrobit.FoodCatalog');
    }

    /**
     * {@inheritDoc}
     */
    public function getSaveValue($value)
    {   
        return $value;
    }

}
