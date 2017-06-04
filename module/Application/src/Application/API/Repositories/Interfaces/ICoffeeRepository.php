<?php

namespace Application\API\Repositories\Interfaces {
    
    use Application\API\Canonicals\Entity\Coffee;
    
    interface ICoffeeRepository {
        public function find($coffeeKey);
        public function findAll();
        public function findAllActive();
        
        public function addCoffee(Coffee $coffee);
        public function updateCoffee(Coffee $coffee);
        public function addOrUpdateCoffee(Coffee $coffee);
        public function toggleActive($coffeeKey);
        public function incrementCoffee($coffeeKey);
        public function decrementCoffee($coffeeKey);
        
        public function exportAllCoffeeToExcel();
        public function doExport($name, \PHPExcel $objPHPExcel);
    }
}

