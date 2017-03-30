<?php

namespace Application\API\Repositories\Interfaces {
    
    use Application\API\Canonicals\Entity\Enquiry;
    
    interface IEnquiryRepository {
        public function search($page = 0, $pageSize = 10, array $orderBy = null);
        public function add(Enquiry $enquiry);
        public function update(Enquiry $enquiry);
        public function addOrUpdate(Enquiry $enquiry);
        public function createEmail(Enquiry $enquiry);
    }
}

