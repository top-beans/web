<?php

namespace Application\API\Repositories\Implementations {

    use Doctrine\ORM\EntityManager,
        Doctrine\Common\Collections\Criteria,
        Application\API\Repositories\Interfaces\IClientsRepository,
        Application\API\Canonicals\Entity\Client,
        Application\API\Canonicals\Dto\ClientSearch;

    class ClientsRepository extends BaseRepository implements IClientsRepository {
        
        public function __construct(EntityManager $em) {
            parent::__construct($em);
        }
        
        public function find($clientkey) {
            return $this->clientsRepo->fetch($clientkey);
        }

        public function findAll() {
            return $this->clientsRepo->fetchAll();
        }

        public function searchByContains(array $criteria, array $orderBy = null, $page = 0, $pageSize = 10) {
            return $this->clientsRepo->searchByContains($criteria, $orderBy, $page, $pageSize);
        }

        public function searchClients(array $criteria, array $orderBy = null, $page = 0, $pageSize = 10) {
            $params = new ClientSearch($criteria);
            $criteriaObj = new Criteria();

            if ($params->getApprovedforbusiness()) {
                $criteriaObj->andWhere($criteriaObj->expr()->eq('approvedforbusiness', 1));
            }

            if ($params->getClientcode() != null) {
                $criteriaObj->andWhere($criteriaObj->expr()->contains('clientcode', $params->getClientcode()));
            }

            if ($params->getSearchtext() != null) {
                $criteriaObj->andWhere($criteriaObj->expr()->orX(
                    $criteriaObj->expr()->contains('name', $params->getSearchtext()),
                    $criteriaObj->expr()->contains('address', $params->getSearchtext()),
                    $criteriaObj->expr()->contains('postcode', $params->getSearchtext())
                ));
            }
            
            if ($orderBy != null) {
                $criteriaObj->orderBy($orderBy);
            }
            
            return $this->clientsRepo->searchByCriteria($criteriaObj, $page, $pageSize);
        }
        
        public function search($page = 0, $pageSize = 10, array $orderBy = null) {
            return $this->clientsRepo->search($page, $pageSize, $orderBy);
        }

        public function addClient(Client $client) {
            $this->clientsRepo->add($client);
        }

        public function updateClient(Client $client) {
            $this->clientsRepo->update($client);
        }

        public function addOrUpdateClient(Client $client) {
            $this->clientsRepo->addOrUpdate($client);
        }

        private function exportToExcel(array $clients) {
            $objPHPExcel = new \PHPExcel();
            $objPHPExcel->setActiveSheetIndex(0);
            
            $sheet = $objPHPExcel->getActiveSheet();
            $sheet->setTitle("Clients");
            
            $sheet->SetCellValue("A1", 'Code');
            $sheet->SetCellValue("B1", 'Name');
            $sheet->SetCellValue("C1", 'Address');
            $sheet->SetCellValue("D1", 'Postcode');
            $sheet->SetCellValue("E1", 'Email');
            $sheet->SetCellValue("F1", 'Number');
            $sheet->SetCellValue("G1", 'Website');
            $sheet->SetCellValue("H1", 'Twitter');
            $sheet->SetCellValue("I1", 'Facebook');
            $sheet->SetCellValue("J1", 'Inc. Date');
            $sheet->SetCellValue("K1", 'Turnover');
            $sheet->SetCellValue("L1", 'Profit');
            $sheet->SetCellValue("M1", 'Loss');
            $sheet->SetCellValue("N1", 'Net Assets');
            $sheet->SetCellValue("O1", 'Share Hld. Funds');
            $sheet->SetCellValue("P1", 'Approved');
            
            $row = 1;
            foreach($clients as $c) {
                $row++;
                $sheet->SetCellValue("A$row", $c->getClientcode());
                $sheet->SetCellValue("B$row", $c->getName());
                $sheet->SetCellValue("C$row", $c->getAddress());
                $sheet->SetCellValue("D$row", $c->getPostcode());
                $sheet->SetCellValue("E$row", $c->getEmail());
                $sheet->SetCellValue("F$row", $c->getNumber());
                $sheet->SetCellValue("G$row", $c->getWebsite());
                $sheet->SetCellValue("H$row", $c->getTwitter());
                $sheet->SetCellValue("I$row", $c->getFacebook());
                $sheet->SetCellValue("J$row", $c->getIncorporationdate() == null ? null : $c->getIncorporationdate()->format("Y-m-d"));
                $sheet->SetCellValue("K$row", $c->getTurnover());
                $sheet->SetCellValue("L$row", $c->getProfit());
                $sheet->SetCellValue("M$row", $c->getLoss());
                $sheet->SetCellValue("N$row", $c->getNetcurrentassets());
                $sheet->SetCellValue("O$row", $c->getShareholdersfunds());
                $sheet->SetCellValue("P$row", $c->getApprovedforbusiness() == 1 ? 'Yes' : 'No');
            }
            
            return $objPHPExcel;
        }
        
        public function exportAllClientsToExcel() {
            return $this->exportToExcel($clients = $this->findAll());
        }

        public function exportClientsToExcel(array $criteria, array $orderBy = null, $page = 0, $pageSize = 10) {
            $searchResult = $this->searchClients($criteria, $orderBy, $page, $pageSize);
            return $this->exportToExcel($searchResult->items);
        }
        
        public function doExport($name, \PHPExcel $objPHPExcel) {
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="' . $name . '"');
            header('Cache-Control: max-age=0');

            $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
            $objWriter->save('php://output');
            exit;
        }
    }
}
