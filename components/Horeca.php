<?php namespace Macrobit\Horeca\Components;

use Lang;
use System\Classes\CombineAssets;
use Cms\Classes\ComponentBase;
use Macrobit\Horeca\Models\Firm as FirmModel;
use Macrobit\Horeca\Models\Tag as TagModel;

class Horeca extends ComponentBase
{

    /**
     * Current page record list
     * @var array
     */
    public $records = [];

    /**
     * Tag list
     * @var array
     */
    public $tags = [];

    /**
     * Current page number
     * @var integer
     */
    public $currentPage = 1;

    /**
     * Total pages
     * @var integer
     */
    public $totalPages = 1;

    /**
     * Total records
     * @var integer
     */
    public $totalRecords = 0;

    /**
     * Firm coordinates for map
     * @var array
     */
    public $coordinates = [];

    /**
     * Search filter
     * @var array
     */
    public $filter = null;

    /**
     * {@inheritDoc}
     */
    public function componentDetails()
    {
        return [
            'name'        =>    Lang::get('macrobit.horeca::lang.horeca.label'),
            'description' =>    Lang::get('macrobit.horeca::lang.horeca.desc')
        ];
    }

    /**
     * {@inheritDoc}
     */
    public function defineProperties()
    {
        return [
            'pageSize' => [
                'title'             => Lang::get('macrobit.horeca::lang.horeca.pageSize.title'),
                'description'       => Lang::get('macrobit.horeca::lang.horeca.pageSize.description'),
                'default'           => 8,
                'type'              => 'string',
                'validationPattern' => '^[0-9]+$',
                'validationMessage' => Lang::get('macrobit.horeca::lang.horeca.pageSize.validationMessage')
            ]
        ];
    }

    /**
     * {@inheritDoc}
     */
    public function onRun()
    {
        $this->addJs('components/assets/js/horeca/macrobit.maps.js', 'Macrobit.Horeca');
        $this->totalPages = $this->getTotalPages();
        $this->totalRecords = FirmModel::count();
        $this->records = $this->toArray($this->getModels());
        $this->tags = $this->getTags();
        $this->coordinates = $this->getCoordinates();
    }

    /**
     * Ajax event handler for pagination
     * @param page 
     */
    public function onPageChanged()
    {
        $filter = post('filter');
        $this->filter = json_encode($filter);
        $this->currentPage = post('page');
        $models = $this->getModels($filter);
        $this->records = $this->toArray($models);
        $this->coordinates = $this->getCoordinates($models);
    }

    /**
     * Ajax event handler for filter
     * @param checkedTags, avgBill
     */
    public function onSearch()
    {
        $filter = [];
        $filter['checkedTags'] = post('checkedTags');
        $filter['avgBill'] = explode(',', post('avgBill'));
        $this->filter = json_encode($filter);

        $this->currentPage = 1;
        $models = $this->getModels($filter);
        $this->records = $this->toArray($models);
        $this->coordinates = $this->getCoordinates($models);
    }

    /**
     * Returns model list for current page considering pageSize param
     * @param  $filter [fields: checkedTags, avgBill]
     * @return array
     */
    private function getModels($filter = null)
    {
        $models = [];
        if ($filter === null || empty($filter)) {
            $models = FirmModel::paginate(
                $this->property('pageSize'), $this->currentPage);
            $this->totalPages = $this->getTotalPages();
            $this->totalRecords = FirmModel::count();
        }
        else if (!empty($filter['checkedTags'])) {
            $dbIt = FirmModel::whereHas('tags', function($query) use ($filter)
            {
                $query->whereIn('id', $filter['checkedTags']);
            })->whereBetween('avg_bill', $filter['avgBill']);
            $this->totalPages = $this->getTotalPages($dbIt->count());
            $this->totalRecords = $dbIt->count();
            $models = $dbIt->paginate(
                $this->property('pageSize'), $this->currentPage);
        }
        else {
            $dbIt = FirmModel::whereBetween('avg_bill', $filter['avgBill']);
            $this->totalPages = $this->getTotalPages($dbIt->count());
            $this->totalRecords = $dbIt->count();
            $models = $dbIt->paginate(
                $this->property('pageSize'), $this->currentPage);
        }
        return $models;
    }

    /**
     * Returns total pages count considering pageSize param 
     * @param integer $totalRecords
     * @return integer
     */
    private function getTotalPages($totalRecords = -1)
    {
        if ($totalRecords === -1) {
            $totalRecords = FirmModel::count();
        }
        if ($totalRecords === 0) {
            return 1;
        }
        return ceil($totalRecords / $this->property('pageSize'));
    }

    /**
     * Returns tag list
     * @return array
     */
    private function getTags()
    {
        return TagModel::where('type', '=', 'firm')->get()->toArray();
    }

    /**
     * Returns coordinates for models in JSON
     * @param  array $models Firms
     * @return string         JSON
     */
    private function getCoordinates($models = null)
    {
        if ($models) {
            return json_encode($models->lists('map_point', 'name'));
        }
        return json_encode(FirmModel::lists('map_point', 'name'));
    }

    /**
     * Creates array of native objects from models
     * @param  array $models Firms
     * @return array         Array of native objects
     */
    private function toArray($models = null)
    {
        if ($models) {
            $records = [];
            foreach ($models as $model) {
                $record = [];
                $record['id'] = $model->id;
                $record['tags'] = implode(', ', $model->tags()->lists('name'));
                $record['name'] = $model->name;
                $record['address'] = $model->address;
                $record['image'] = $model->images->first();
                $record['phone'] = $model->phone;
                array_push($records, $record);
            }
            return $records;
        }
        return null;
    }

}