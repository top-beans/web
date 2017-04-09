<?php

namespace Application\API\Canonicals\Dto {
    
    class CoffeeSearch  { 
        
        private $searchtext;
        private $active;

        public function __construct(array $criteria) {
            $this->searchtext = $criteria['searchtext'];
            
            switch (strtolower($criteria['active']))
            {
                case 'true': case '1': case 1: case 'on': case 'yes': {
                    $this->active = true;
                    break;
                }
                default: {
                    $this->active = false;
                    break;
                }
            }
        }
        
        function getSearchtext() { return $this->searchtext; } 
        function getActive() { return $this->active; } 
    }
}
