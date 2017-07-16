<?php

namespace Application\API\Repositories\Implementations {

    use Doctrine\ORM\EntityManagerInterface;
    use Doctrine\ORM\EntityRepository;
    use Doctrine\ORM\Mapping\ClassMetadata;
    use Application\API\Canonicals\Entity\Order;
    use Application\API\Canonicals\Entity\Orderheaderview;
    use Application\API\Repositories\Base\IRepository;
    use Application\API\Repositories\Base\Repository;
    use Application\API\Repositories\Interfaces\IEMailService;
    use Application\API\Repositories\Interfaces\IOrderEmailsService;
    use Application\API\Repositories\Interfaces\ICancellationsRepository;

    class CancellationsRepository implements ICancellationsRepository {
        
        /**
         * @var EntityManagerInterface 
         */
        private $em;
        
        /**
         * @var IEMailService
         */
        private $emailSvc;
        
        /**
         * @var IOrderEmailsService
         */
        private $orderEmailsSvc;
        
        /**
         * @var integer
         */
        private $confirmWindow;
        
        /**
         * @var integer
         */
        private $cancelWindow;
        
        /**
         * @var IRepository        
         */
        private $ordersRepo;
        
        /**
         * @var IRepository
         */
        private $orderHeaderViewRepo;
        
        public function __construct(EntityManagerInterface $em, IEMailService $emailSvc, IOrderEmailsService $orderEmailsSvc, $confirmWindow, $cancelWindow) {
            $this->em = $em;
            $this->emailSvc = $emailSvc;
            $this->orderEmailsSvc = $orderEmailsSvc;
            $this->confirmWindow = $confirmWindow;
            $this->cancelWindow = $cancelWindow;
            $this->ordersRepo = new Repository($em, new EntityRepository($em, new ClassMetadata(get_class(new Order()))));
            $this->orderHeaderViewRepo = new Repository($em, new EntityRepository($em, new ClassMetadata(get_class(new Orderheaderview()))));
        }

        public function getNewCancellationCode() {
            do {
                $char = str_shuffle("0123456789");
                $code = "";
                $length = 6;
                
                for($i = 0, $l = strlen($char) - 1; $i < $length; $i ++) {
                    $code .= strtoupper($char{mt_rand(0, $l)});
                }

            } while ($this->ordersRepo->count(['cancellationcode' => $code]) > 0);
            
            return $code;
        }

        public function confirmGroupKey($groupKey) {
            return $this->orderHeaderViewRepo->findOneBy(['groupkey' => $groupKey]) != null;
        }

        public function confirmCode($code) {
            try {
                $this->em->getConnection()->beginTransaction();
                
                $orderHeader = $this->orderHeaderViewRepo->findOneBy(['cancellationcode' => $code]);
                $now = new \DateTime("now", new \DateTimeZone("UTC"));

                if ($orderHeader == null) {
                    return false;
                } else if ($this->confirmWindow < $now->diff($orderHeader->getCoderequesttime())->i) {
                    return false;
                }
                
                foreach($this->ordersRepo->findBy(['cancellationcode' => $code]) as $orderItem) {
                    $orderItem->setCodeconfirmtime($now);
                }
                
                $this->em->flush();
                $this->em->getConnection()->commit();
                
                return true;
                
            } catch (\Exception $ex) {
                $this->em->getConnection()->rollBack();
                throw $ex;
            }
        }

        public function isAuthenticated($code) {
            $orderHeader = $this->orderHeaderViewRepo->findOneBy(['cancellationcode' => $code]);
            $now = new \DateTime("now", new \DateTimeZone("UTC"));

            if ($orderHeader == null) {
                return false;
            } else if ($this->cancelWindow < $now->diff($orderHeader->getCodeconfirmtime())->i) {
                return false;
            } else {
                return true;
            }
        }

        public function createAndSendCode($groupKey) {
            try {
                $this->em->getConnection()->beginTransaction();

                $orderHeader = $this->orderHeaderViewRepo->findOneBy(['groupkey' => $groupKey]);

                if ($orderHeader == null) {
                    throw new \Exception("Could not find the order");
                }
            
                $code = $this->getNewCancellationCode();
                
                foreach($this->ordersRepo->findBy(['groupkey' => $groupKey]) as $orderItem) {
                    $orderItem->setCancellationcode($code);
                    $orderItem->setCoderequesttime(new \DateTime("now", new \DateTimeZone("UTC")));
                    $orderItem->setCodeconfirmtime(null);
                }
                
                $shoppersCopy = $this->orderEmailsSvc->createConfirmationCodeEmail($code, $this->confirmWindow, $orderHeader->getDeliveryemail());
                $this->emailSvc->sendMail($shoppersCopy);
                
                $this->em->flush();
                $this->em->getConnection()->commit();
                
            } catch (\Exception $ex) {
                $this->em->getConnection()->rollBack();
                throw $ex;
            }
        }

        public function getMaskedEmail($groupKey) {
            $orderHeader = $this->orderHeaderViewRepo->findOneBy(['groupkey' => $groupKey]);

            if ($orderHeader == null) {
                throw new \Exception("Could not find the order");
            }
            
            $pieces = explode("@", $orderHeader->getDeliveryemail());
            
            $name = $pieces[0];
            $domn = $pieces[1];
            $len  = floor(strlen($name)/2);
        
            return substr($name, 0, $len) . str_repeat('*', $len) . "@$domn";
        }
    }
}
