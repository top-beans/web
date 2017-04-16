<?php

namespace Application\API\Repositories\Implementations {

    use Doctrine\ORM\EntityManager,
        Doctrine\ORM\EntityRepository,
        Doctrine\ORM\Mapping\ClassMetadata,
        Application\API\Repositories\Interfaces\ICartRepository,
        Application\API\Repositories\Base\IRepository,
        Application\API\Repositories\Base\Repository,
        Application\API\Canonicals\Entity\Shoppingcart,
        Application\API\Canonicals\Entity\Coffee,
        Application\API\Canonicals\Entity\RequestTypes,
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
        protected $coffeeRepo;
        
        /**
         * @var IRepository
         */
        protected $cartViewRepo;
        
        public function __construct(EntityManager $em) {
            $this->em = $em;
            $this->cartRepo = new Repository($em, new EntityRepository($em, new ClassMetadata(get_class(new Shoppingcart()))));
            $this->coffeeRepo = new Repository($em, new EntityRepository($em, new ClassMetadata(get_class(new Coffee()))));
            $this->cartViewRepo = new Repository($em, new EntityRepository($em, new ClassMetadata(get_class(new Shoppingcartview()))));
        }

        public function validateMergeCart(Shoppingcart $cart) {
            if($cart->getCookiekey() == null || $cart->getCoffeekey() == null) {
                return ["Your cart is missing either a coffee or cookie key (coffee goes with cookies bruv!)"];
            } 
            
            if ($this->coffeeRepo->findOneBy(['coffeekey' => $cart->getCoffeekey(), 'active' => 1]) == null) {
                return ["Your coffee could not be found"];
            }
            
            $matching = $this->cartRepo->findOneBy([
                'cookiekey' => $cart->getCookiekey(),
                'coffeekey' => $cart->getCoffeekey(),
                'active' => 1
            ]);

            if ($matching == null) {
                return [];
            }
            
            $errors = [];
            
            if ($matching->getRequesttypekey() == RequestTypes::Sample) {
                if ($cart->getRequesttypekey() == RequestTypes::Sample) {
                    $errors[] = "Sample already on Cart";
                } else if ($cart->getQuantity() <= 0) {
                    $errors[] = "A valid quantity greater than zero is required";
                }
            } else if ($matching->getRequesttypekey() == RequestTypes::Purchase) {
                if ($cart->getRequesttypekey() == RequestTypes::Purchase && $cart->getQuantity() <= 0) {
                    $errors[] = "A valid quantity greater than zero is required";
                }
            }
            
            return $errors;
        }
        
        public function mergeCart(Shoppingcart $cart) {
            $matching = $this->cartRepo->findOneBy([
                'cookiekey' => $cart->getCookiekey(),
                'coffeekey' => $cart->getCoffeekey(),
                'active' => 1
            ]);

            if ($matching == null) {
                $this->cartRepo->add($cart);
            } else {
                $cart->setShoppingcartkey($matching->getShoppingcartkey());
                $this->cartRepo->update($cart);
            }
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
