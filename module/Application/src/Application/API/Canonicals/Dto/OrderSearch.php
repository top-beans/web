<?php

namespace Application\API\Canonicals\Dto {
    
    class OrderSearch  { 
        
        private $searchtext;
        private $status;

        public function __construct(array $criteria) {
            $this->searchtext = $criteria['searchtext'];
            $this->status = $criteria['status'];
        }
        
        function getSearchtext() { return $this->searchtext; } 
        function getStatus() { return $this->status; } 

        function setSearchtext($val) { $this->searchtext  = $val; } 
        function setStatus($val) { $this->status = $val; } 
    }
}
