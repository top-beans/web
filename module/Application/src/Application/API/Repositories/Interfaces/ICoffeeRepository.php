<?php

namespace Application\API\Repositories\Interfaces {
    
    use Application\API\Canonicals\Entity\Coffee;
    
    interface ICoffeeRepository {
        public function find($coffeeKey);
        public function findAll();
        public function searchCoffee(array $criteria, array $orderBy = null, $page = 0, $pageSize = PHP_INT_MAX);
        
        public function addCoffee(Coffee $coffee);
        public function updateCoffee(Coffee $coffee);
        public function addOrUpdateCoffee(Coffee $coffee);
        public function deactivateCoffee($coffeeKey);
        
        public function exportAllCoffeeToExcel();
        public function exportCoffeeToExcel(array $criteria, array $orderBy = null, $page = 0, $pageSize = 10);
        public function doExport($name, \PHPExcel $objPHPExcel);
    }
}

