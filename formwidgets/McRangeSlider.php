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
     * [$margin, $step, $min, $max description]
     * @var field properties
     */
    public $margin = 60;
    public $step = 15;
    public $min = 0;
    public $max = 1440;

    /**
     * {@inheritDoc}
     */
    public function init()
    {
        $this->fillFromConfig([
            'margin',
            'step',
            'min',
            'max'
        ]);
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
        $this->vars['margin'] = $this->margin;
        $this->vars['step'] = $this->step;
        $this->vars['min'] = $this->min;
        $this->vars['max'] = $this->max;
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
