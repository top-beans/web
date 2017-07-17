<?php

namespace Application\API\Repositories\Implementations {

    use Doctrine\ORM\EntityManagerInterface,
        Doctrine\ORM\EntityRepository,
        Doctrine\ORM\Mapping\ClassMetadata,
        Application\API\Canonicals\Entity\Enquiry,
        Application\API\Canonicals\Entity\Coffee,
        Application\API\Repositories\Base\IRepository,
        Application\API\Repositories\Base\Repository,
        Application\API\Repositories\Interfaces\IEnquiryRepository,
        Application\API\Canonicals\Dto\EmailRequest;

    class EnquiryRepository implements IEnquiryRepository {
        
        /**
         * @var string
         */
        private $supportEmail;
        
        /**
         * @var string
         */
        private $domainName;
        
        /**
         * @var string
         */
        private $isDevelopment;
        
        /**
         * @var IRepository
         */
        private $enquiryRepo;
        
        /**
         * @var IRepository
         */
        private $coffeeRepo;
        
        /**
         * @var EntityManagerInterface
         */
        private $em;
        
        public function __construct(EntityManagerInterface $em, $supportEmail, $domainName, $isDevelopment) {
            $this->em = $em;
            $this->supportEmail = $supportEmail;
            $this->domainName = $domainName;
            $this->isDevelopment = $isDevelopment;
            $this->enquiryRepo = new Repository($em, new EntityRepository($em, new ClassMetadata(get_class(new Enquiry()))));
            $this->coffeeRepo = new Repository($em, new EntityRepository($em, new ClassMetadata(get_class(new Coffee()))));
        }

        public function add(Enquiry $enquiry) {
            $this->enquiryRepo->add($enquiry);            
        }

        public function addCoffeeEnquiry(Enquiry $enquiry, $coffeeKey) {
            $coffee = $this->coffeeRepo->fetch($coffeeKey);

            if ($coffee == null) {
                throw new \Exception("Could not find coffee");
            }

            $prepend = "PREPENDED: Enquiry about http://$this->domainName/Admin/coffees/$coffeeKey";
            $enquiry->setDescription("$prepend\n\n" . $enquiry->getDescription());
            $this->enquiryRepo->add($enquiry);

            return $enquiry;
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
            $domainPath = ($this->isDevelopment ? "http" : "https") . "://$this->domainName";
            $name = $enquiry->getName();
            $number = $enquiry->getNumber();
            $email = $enquiry->getEmail();
            $date = $enquiry->getCreateddate()->format("d/M/y h:i a");
            $description = nl2br($enquiry->getDescription());
            
            $template = new TemplateEngine("data/templates/enquiry-alert.phtml", [
                'title' => "Enquiry Alert",
                'domainPath' => $domainPath,
                'name' => $name,
                'number' => $number,
                'email' => $email,
                'date' => $date,
                'description' => $description
            ]);
            
            $request = new EmailRequest();
            $request->recipient = $this->supportEmail;
            $request->subject = "New Enquiry from TopBeans.co.uk";
            $request->htmlbody = $template->render();
            $request->textbody = null;
            
            return $request;
        }
    }
}