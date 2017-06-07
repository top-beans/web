<?php

namespace Application\API\Canonicals\Dto {
    use JMS\Serializer\Annotation\Type;
    
    class OrderItem { 
        
        /**
         * @Type("string")
         */
        public $groupkey;
        
        /**
         * @Type("string")
         */
        public $coffeekey;
    }
}
