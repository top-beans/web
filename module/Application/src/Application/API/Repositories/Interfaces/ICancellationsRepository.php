<?php

namespace Application\API\Repositories\Interfaces {
    
    interface ICancellationsRepository {
        public function getNewCancellationCode();
        
        public function confirmGroupKey($groupKey);
        public function confirmCode($code);
        
        public function isAuthenticated($code);
        
        public function createAndSendCode($groupKey);
        public function getMaskedEmail($groupKey);
    }
}
