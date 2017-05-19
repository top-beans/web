<?php

namespace Application\API\Canonicals\Dto {
    use JMS\Serializer\Annotation\Type;
    
    class OrderResult { 
        
        /**
         * @Type("string")
         */
        public $requirespayment;
        
        /**
         * @Type("string")
         */
        public $groupkey;
    }
}
