<?php

namespace Application\API\Canonicals\Dto {
    use JMS\Serializer\Annotation\Type;
    
    class Captcha { 
        
        /**
         * @Type("string")
         */
        public $captchaname;
        
        /**
         * @Type("string")
         */
        public $captchatoken;
        
        /**
         * @Type("string")
         */
        public $captchaword;
        
        /**
         * @Type("string")
         */
        public $captcharesponse;
    }
}
