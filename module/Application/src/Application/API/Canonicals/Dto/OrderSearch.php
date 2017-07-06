<?php

namespace Application\API\Canonicals\Dto {
    
    class OrderSearch  { 
        
        private $searchtext;
        private $statuses;

        public function __construct(array $criteria) {
            $this->searchtext = $criteria['searchtext'];
            $this->statuses = $criteria['statuses'];
        }
        
        function getSearchtext() { return $this->searchtext; } 
        function getStatuses() { return $this->statuses; } 

        function setSearchtext($val) { $this->searchtext  = $val; } 
        function setStatuses($val) { $this->statuses = $val; } 
    }
}
