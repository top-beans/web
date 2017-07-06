<?php
namespace Application\API\Canonicals\Entity {
    
    class OrderStatuses {
        const Creating = 1;
        const Received = 2;
        const Dispatched = 3;
        const Cancelled = 4;
        const SentForRefund = 5;
        const Refunded = 6;
        const Returned = 7;
        const SentForRefundFailed = 8;
    }
}
