<?php

namespace Application\API\Repositories\Interfaces {
    
    use Application\API\Canonicals\Dto\CancellationDetails;
    
    interface ICancellationsRepository {
        public function confirmCancellation($code);
        public function getOrder(CancellationDetails $details);
        public function cancelItem(CancellationDetails $details);
        public function cancelOrder(CancellationDetails $details);
    }
}
