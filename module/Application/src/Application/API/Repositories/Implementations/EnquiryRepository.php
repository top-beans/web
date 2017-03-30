<?php

namespace Application\API\Repositories\Implementations {

    use Doctrine\ORM\EntityManager,
        Application\API\Canonicals\Entity\Enquiry,
        Application\API\Repositories\Interfaces\IEnquiryRepository,
        Application\API\Canonicals\Dto\EmailRequest;

    class EnquiryRepository extends BaseRepository implements IEnquiryRepository {
        
        private $supportEmail;
        
        public function __construct(EntityManager $em, $supportEmail) {
            parent::__construct($em);
            $this->supportEmail = $supportEmail;
        }

        public function add(Enquiry $enquiry) {
            $this->enquiryRepo->add($enquiry);            
        }

        public function addOrUpdate(Enquiry $enquiry) {
            $this->enquiryRepo->addOrUpdate($enquiry);            
        }

        public function search($page = 0, $pageSize = 10, array $orderBy = null) {
            return $this->enquiryRepo->search($page, $pageSize, $orderBy);
        }

        public function update(Enquiry $enquiry) {
            $this->enquiryRepo->update($enquiry);            
        }

        public function createEmail(Enquiry $enquiry) {
            $request = new EmailRequest();
            $request->recipient = $this->supportEmail;
            $request->subject = "New Enquiry from TopBeans.co.uk";
            $request->htmlbody = "
                <html>
                <head></head>
                <body>
                <p>Salam Aleikum,</p>
                <p>The following request has been submitted:</p>
                <ul>
                <li><strong>Person:</strong> " . $enquiry->getName() . "</li>
                <li><strong>Phone:</strong> " . $enquiry->getNumber() . "</li>
                <li><strong>Email:</strong> " . $enquiry->getEmail() . "</li>
                <li><strong>Date:</strong> " . $enquiry->getCreateddate()->format("d/M/y h:i a") . "</li>
                <li><strong>Description:</strong> <br/>" . $enquiry->getDescription() . "</li>
                </ul>
                <p>Jazakallah Kheir</p>
                <p>Top Beans Support Team</p>
                </body>
                </html>
            ";
            return $request;
        }
    }
}