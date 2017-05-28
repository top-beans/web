<?php

namespace Application\API\Canonicals\Dto {
    use JMS\Serializer\Annotation\Type;
    
    class CoffeeEnquiry { 
        
        /**
         * @Type("Application\API\Canonicals\Entity\Enquiry")
         */
        public $enquiry;
        
        /**
         * @Type("string")
         */
        public $coffeekey;
    }
}
