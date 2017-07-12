<?php

namespace Application\API\Canonicals\Dto {
    use JMS\Serializer\Annotation\Type;
    
    class OrderItems { 
        
        /**
         * @Type("string")
         */
        public $groupkey;
        
        /**
         * @Type("array")
         */
        public $coffeekeys;
    }
}
