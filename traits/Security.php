<?php namespace Macrobit\Horeca\Traits;


trait Security 
{
	
    public function listExtendQuery($query)
    {
        $this->extendQuery($query);
    }

    public function formExtendQuery($query)
    {
        $this->extendQuery($query);
    }

    private function extendQuery($query)
    {
       if (!$this->user->hasAnyAccess(['macrobit.horeca.manager'])) {
            $query->whereHas('firm', function($q)
            {
                $q->where('id', '=', $this->user->firm->id);   
            });
       }      
    }

}