<?php

namespace Application\API\Canonicals\Dto {
    use JMS\Serializer\Annotation\Type;
    
    class EmailRequest { 
        
        /**
         * @Type("string")
         */
        public $recipient;

        /**
         * @Type("string")
         */
        public $subject;
        
        /**
         * @Type("string")
         */
        public $textbody;
        
        /**
         * @Type("string")
         */
        public $htmlbody;
    }
}
