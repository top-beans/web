<?php

namespace Application\API\Repositories\Implementations {

    use Doctrine\ORM\EntityManager,
        Doctrine\ORM\EntityRepository,
        Doctrine\ORM\Mapping\ClassMetadata,
        Application\API\Repositories\Interfaces\ICartRepository,
        Application\API\Repositories\Base\IRepository,
        Application\API\Repositories\Base\Repository,
        Application\API\Canonicals\Entity\Shoppingcart,
        Application\API\Canonicals\Entity\Shoppingcartview;

    class CartRepository implements ICartRepository {
        
        /**
         * @var EntityManager 
         */
        protected $em;
        
        /**
         * @var IRepository
         */
        protected $cartRepo;
        
        /**
         * @var IRepository
         */
        protected $cartViewRepo;
        
        public function __construct(EntityManager $em) {
            $this->em = $em;
            $this->cartRepo = new Repository($em, new EntityRepository($em, new ClassMetadata(get_class(new Shoppingcart()))));
            $this->cartViewRepo = new Repository($em, new EntityRepository($em, new ClassMetadata(get_class(new Shoppingcartview()))));
        }

        public function addOrUpdateCart(Shoppingcart $cart) {
            $this->cartRepo->addOrUpdate($cart);
        }

        public function addToCart(Shoppingcart $cart) {
            $this->cartRepo->add($cart);
        }

        public function deleteFromCart($cookiekey, $coffeeKey) {
            $items = $this->cartRepo->findBy(['cookiekey' => $cookiekey, 'coffeekey' => $coffeeKey]);
            
            if ($items == null || count($items) == 0) {
                return;
            } else {
                $this->cartRepo->deleteList($items);
            }
        }

        public function getCart($cookieKey) {
            return $this->cartViewRepo->findBy(['cookiekey' => $cookieKey]);
        }

        public function getCartSize($cookieKey) {
            return $this->cartViewRepo->count(['cookiekey' => $cookieKey]);
        }

        public function updateCart(Shoppingcart $cart) {
            $this->cartRepo->update($cart);
        }
    }
}
