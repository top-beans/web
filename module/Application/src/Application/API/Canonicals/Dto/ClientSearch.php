<?php

namespace Application\API\Canonicals\Dto {
    
    class ClientSearch  { 
        
        private $searchtext;
        private $clientcode;
        private $approvedforbusiness;

        public function __construct(array $criteria) {
            $this->searchtext = $criteria['searchtext'];
            $this->clientcode = $criteria['clientcode'];
            
            switch (strtolower($criteria['approvedforbusiness']))
            {
                case 'true': case '1': case 1: case 'on': case 'yes': {
                    $this->approvedforbusiness = true;
                    break;
                }
                default: {
                    $this->approvedforbusiness = false;
                    break;
                }
            }
        }
        
        function getSearchtext() { return $this->searchtext; } 
        function getClientcode() { return $this->clientcode; } 
        function getApprovedforbusiness() { return $this->approvedforbusiness; } 

        function setSearchtext($val) { $this->searchtext  = $val; } 
        function setClientcode($val) { $this->clientcode = $val; } 
        function setApprovedforbusiness($val) { $this->approvedforbusiness = $val; } 
    }
}
