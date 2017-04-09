<?php

namespace Application\API\Repositories\Implementations {

    use Doctrine\ORM\EntityManager,
        Doctrine\ORM\EntityRepository,
        Doctrine\ORM\Mapping\ClassMetadata,
        Doctrine\Common\Collections\Criteria,
        Application\API\Repositories\Interfaces\ICoffeeRepository,
        Application\API\Canonicals\Entity\Coffee,
        Application\API\Repositories\Base\IRepository,
        Application\API\Canonicals\Dto\CoffeeSearch;

    class CoffeeRepository implements ICoffeeRepository {
        
        /**
         * @var EntityManager 
         */
        protected $em;
        
        /**
         * @var IRepository
         */
        protected $coffeeRepo;
        
        public function __construct(EntityManager $em) {
            $this->em = $em;
            $this->coffeeRepo = new Repository($em, new EntityRepository($em, new ClassMetadata(get_class(new Coffee()))));
        }

        public function addCoffee(Coffee $coffee) {
            $this->coffeeRepo->add($coffee);
        }

        public function updateCoffee(Coffee $coffee) {
            $this->coffeeRepo->update($coffee);
        }

        public function addOrUpdateCoffee(Coffee $coffee) {
            $this->coffeeRepo->update($coffee);
        }

        public function deactivateCoffee($coffeeKey) {
            $coffee = $this->coffeeRepo->fetch($coffeeKey);
            
            if ($coffee == null) {
                return;
            } else {
                $coffee->setActive(0);
                $this->updateCoffee($coffee);
            }
        }

        public function doExport($name, \PHPExcel $objPHPExcel) {
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="' . $name . '"');
            header('Cache-Control: max-age=0');

            $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
            $objWriter->save('php://output');
            exit;
        }

        public function exportAllCoffeeToExcel() {
            return $this->exportToExcel($coffees = $this->findAll());
        }

        public function exportCoffeeToExcel(array $criteria = [], array $orderBy = null, $page = 0, $pageSize = PHP_INT_MAX) {
            $searchResult = $this->searchCoffee($criteria, $orderBy, $page, $pageSize);
            return $this->exportToExcel($searchResult->items);
        }

        private function exportToExcel(array $coffee) {
            $objPHPExcel = new \PHPExcel();
            $objPHPExcel->setActiveSheetIndex(0);
            
            $sheet = $objPHPExcel->getActiveSheet();
            $sheet->setTitle("Clients");
            
            $sheet->SetCellValue("A1", 'Packaging');
            $sheet->SetCellValue("B1", 'Availability');
            $sheet->SetCellValue("C1", 'Warehouse');
            $sheet->SetCellValue("D1", 'Screensize');
            $sheet->SetCellValue("E1", 'Available Amt');
            $sheet->SetCellValue("F1", 'Cropyear');
            $sheet->SetCellValue("G1", 'Cuppingscore');
            $sheet->SetCellValue("H1", 'Currency');
            $sheet->SetCellValue("I1", 'Price');
            $sheet->SetCellValue("J1", 'Name');
            $sheet->SetCellValue("K1", 'Description');
            $sheet->SetCellValue("L1", 'Processing');
            $sheet->SetCellValue("M1", 'Country');
            $sheet->SetCellValue("N1", 'Region');
            $sheet->SetCellValue("O1", 'Producer');
            $sheet->SetCellValue("P1", 'Prc Base Unit');
            $sheet->SetCellValue("Q1", 'Sensorial Desc');
            $sheet->SetCellValue("R1", 'Cultivars');
            $sheet->SetCellValue("S1", 'Active');
            
            $row = 1;
            foreach($coffee as $c) {
                $row++;
                $sheet->SetCellValue("A$row", $c->getPackaging());
                $sheet->SetCellValue("B$row", $c->getAvailability());
                $sheet->SetCellValue("C$row", $c->getWarehouse());
                $sheet->SetCellValue("D$row", $c->getScreensize());
                $sheet->SetCellValue("E$row", $c->getAvailableamount());
                $sheet->SetCellValue("F$row", $c->getCropyear());
                $sheet->SetCellValue("G$row", $c->getCuppingscore());
                $sheet->SetCellValue("H$row", $c->getCurrency());
                $sheet->SetCellValue("I$row", $c->getPrice());
                $sheet->SetCellValue("J$row", $c->getName());
                $sheet->SetCellValue("K$row", $c->getDescription());
                $sheet->SetCellValue("L$row", $c->getProcessingmethod());
                $sheet->SetCellValue("M$row", $c->getCountry());
                $sheet->SetCellValue("N$row", $c->getRegion());
                $sheet->SetCellValue("O$row", $c->getProducer());
                $sheet->SetCellValue("P$row", $c->getPricebaseunit());
                $sheet->SetCellValue("Q$row", $c->getSensorialdescriptors());
                $sheet->SetCellValue("R$row", $c->getCultivars());
                $sheet->SetCellValue("S$row", $c->getActive() == 1 ? 'Yes' : 'No');
            }
            
            return $objPHPExcel;
        }
        
        public function find($coffeeKey) {
            return $this->coffeeRepo->fetch($coffeeKey);
        }

        public function findAll() {
            return $this->coffeeRepo->fetchAll();
        }

        public function searchCoffee(array $criteria = [], array $orderBy = null, $page = 0, $pageSize = PHP_INT_MAX) {
            $params = new CoffeeSearch($criteria);
            $criteriaObj = new Criteria();

            if ($params->getActive()) {
                $criteriaObj->andWhere($criteriaObj->expr()->eq('active', 1));
            }

            if ($params->getSearchtext() != null) {
                $criteriaObj->andWhere($criteriaObj->expr()->orX(
                    $criteriaObj->expr()->contains('name', $params->getSearchtext()),
                    $criteriaObj->expr()->contains('description', $params->getSearchtext()),
                    $criteriaObj->expr()->contains('region', $params->getSearchtext())
                ));
            }
            
            if ($orderBy != null) {
                $criteriaObj->orderBy($orderBy);
            }
            
            return $this->coffeeRepo->searchByCriteria($criteriaObj, $page, $pageSize);
        }
    }
}
