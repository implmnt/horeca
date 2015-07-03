<?php namespace Macrobit\Horeca\Components;

use Lang;
use Auth;
use Cms\Classes\ComponentBase;
use Macrobit\Horeca\Models\Firm as FirmModel;
use Macrobit\Horeca\Models\Node as NodeModel;
use Macrobit\Horeca\Models\Comment as CommentModel;

class FirmDetail extends ComponentBase
{

    public $name = null;

    public $description = null;

    public $address = null;

    public $phone = null;

    public $bill = null;

    public $prices = null;

    public $images = null;

    public $nodes = null;

    public $currentPage = 1;

    public $totalPages = 1;

    public $currentNode = null;

    public $comments = null;

    public $currentCommentPage = 1;
    
    public $totalCommentPages = 1;

    public $rating = null;


    /**
     * {@inheritDoc}
     */
    public function componentDetails()
    {
        return [
            'name'        =>    Lang::get('macrobit.horeca::lang.firmdetail.label'),
            'description' =>    Lang::get('macrobit.horeca::lang.firmdetail.desc')
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
                'default'           => 9,
                'type'              => 'string',
                'validationPattern' => '^[0-9]+$',
                'validationMessage' => Lang::get('macrobit.horeca::lang.horeca.pageSize.validationMessage')
            ],            
            'commentPageSize' => [
                'title'             => Lang::get('macrobit.horeca::lang.horeca.commentPageSize.title'),
                'description'       => Lang::get('macrobit.horeca::lang.horeca.pageSize.description'),
                'default'           => 4,
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
        $firmId = $this->param('id');
        if (!$firmId) {
            return;
        }

        $firm = FirmModel::find($firmId);
        
        $this->name = $firm->name;
        $this->description = $firm->description;
        $this->address = $firm->address;
        $this->phone = $firm->phone;
        $this->images = $firm->images;
        $this->comments = $this->getComments();
        $this->bill = $firm->avg_bill;

        $this->nodes = $this->getNodes();

        $nodeId = empty($this->nodes) ? null : $this->nodes[0]->id;

        $this->prices = $this->getPrices($firm, null, 0, $nodeId);

        $this->currentNode = $this->getRootNode($nodeId);

        $this->totalPages = $this->getTotalPages();

        $this->totalCommentPages = $this->getTotalCommentPages();

        $this->rating = $firm->getRating();
    }

    public function onNodeChanged()
    {
        $firmId = $this->param('id');
        if (!$firmId) {
            return;
        }

        $this->nodes = $this->getNodes();

        $nodeId = post('nodeId');
        $this->currentNode = $this->getRootNode($nodeId);
        $this->prices = $this->getPrices(null, null, 0, $nodeId);
        $this->currentPage = 1;
        $this->totalPages = $this->getTotalPages();
    }

    public function onPageChanged()
    {
        $page = post('page');
        $nodeId = post('nodeId');
        $this->currentNode = $this->getRootNode($nodeId);
        $this->prices = $this->getPrices(null, null, $page, $nodeId);
        $this->currentPage = $page;
        $this->totalPages = $this->getTotalPages();
    }

    public function onCommentPageChanged()
    {
        $page = post('page');
        $this->currentCommentPage = $page;
        $this->comments = $this->getComments();
        $this->totalCommentPages = $this->getTotalCommentPages();
    }

    public function onComment()
    {
        $firm = FirmModel::find($this->param('id'));
        $content = post('comment');
        $rating = post('rating');
        $comment = new CommentModel;
        $comment->content = $content;
        $comment->rating = $rating;
        $comment->user = Auth::getUser();
        $comment->save();
        $firm->comments()->save($comment);

        $this->comments = $this->getComments();
        $this->totalCommentPages = $this->getTotalCommentPages();
        $this->currentCommentPage = 1;
        $this->onRun();
    }

    private function getNodes()
    {
        $nodes = [];
        $allNodes = NodeModel::make()->getEagerRoot();
        foreach ($allNodes as $node) {
            if ($node->firm && $node->firm->id === $this->param('id')) {
                array_push($nodes, $node);
            }
        }
        return $nodes;
    }

    private function getPrices($firm = null, $size = null, $page = 0, $nodeId = null)
    {
        if ($size == null) {
            $size = $this->property('pageSize');
        }

        if ($firm == null) {
            $firm = FirmModel::find($this->param('id'));
        }

        if ($nodeId == null) {
            return $firm->prices()->with('images', 'tags')->paginate($size, $page);
        }
        $tagsIds = NodeModel::find($nodeId)->tags()->lists('id');
        $prices = $firm->prices()->whereHas('tags', function($q) use ($tagsIds){
            $q->whereIn('id', $tagsIds);
        });
        return $prices->paginate($size, $page);
    }

    private function getTotalPages()
    {
        if ($this->prices) {
            return ceil($this->prices->total() / $this->property('pageSize'));
        }
        return ceil($this->getPrices()->total() / $this->property('pageSize'));
    }

    private function getRootNode($nodeId)
    {
        if ($nodeId) {
            $node = NodeModel::find($nodeId);
            return $node->getRoot()->id;
        }
        return;
    }

    private function getTotalCommentPages()
    {
        if ($this->comments) {
            return ceil($this->comments->total() / $this->property('commentPageSize'));
        }
        return ceil($this->getComments()->total() / $this->property('commentPageSize'));
    }

    private function getComments($firm = null, $size = null, $page = 0)
    {
        if ($size == null) {
            $size = $this->property('commentPageSize');
        }

        if ($firm == null) {
            $firm = FirmModel::find($this->param('id'));
        }

        return $firm->comments()->orderBy('date', 'desc')->paginate($size, $page);
    }

}