<?php

namespace Application\API\Canonicals\Dto {
    
    use JMS\Serializer\Annotation\AccessType;
    use JMS\Serializer\Annotation\Type;
    
    /**
     * @AccessType("public_method")
     */
    class Credentials { 
        
        /**
         * @Type("string")
         */
        private $username;
        
        /**
         * @Type("string")
         */
        private $password;
        
        public function __construct($username, $password) {
            $this->username = $username;
            $this->password = $password;
        }
        
        public function getUsername() {
            return $this->username; 
        }
        
        public function getPassword() {
            return $this->password;
        }
        
        public function setUsername($val) {
            $this->username = $val; 
        }
        
        public function setPassword($val) {
            $this->password = $val; 
        }
    }
}
