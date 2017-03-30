<?php

namespace Application\API\Repositories\Interfaces {
    
    use Application\API\Canonicals\Entity\Client;
    
    interface IClientsRepository {
        public function find($clientkey);
        public function findAll();
        
        public function searchByContains(array $criteria, array $orderBy = null, $page = 0, $pageSize = 10);
        public function searchClients(array $criteria, array $orderBy = null, $page = 0, $pageSize = 10);
        public function search($page = 0, $pageSize = 10, array $orderBy = null);
        
        public function addClient(Client $client);
        public function updateClient(Client $client);
        public function addOrUpdateClient(Client $client);
        
        public function exportAllClientsToExcel();
        public function exportClientsToExcel(array $criteria, array $orderBy = null, $page = 0, $pageSize = 10);
        public function doExport($name, \PHPExcel $objPHPExcel);
    }
}

