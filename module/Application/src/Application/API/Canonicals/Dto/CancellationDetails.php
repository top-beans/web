<?php

namespace Application\API\Canonicals\Dto {
    use JMS\Serializer\Annotation\Type;
    
    class CancellationDetails { 
        
        /**
         * @Type("string")
         */
        public $code;
        
        /**
         * @Type("string")
         */
        public $coffeekey;
    }
}
