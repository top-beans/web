<?php

namespace Application\API\Canonicals\Dto {
    use JMS\Serializer\Annotation\Type;
    
    class CartItem { 
        
        /**
         * @Type("string")
         */
        public $cookiekey;
        
        /**
         * @Type("string")
         */
        public $coffeekey;
    }
}
