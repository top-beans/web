<?php

namespace Application\API\Repositories\Implementations {

    use Doctrine\ORM\EntityManager,
        Doctrine\ORM\EntityRepository,
        Doctrine\ORM\Mapping\ClassMetadata,
        Application\API\Repositories\Interfaces\ICoffeeRepository,
        Application\API\Canonicals\Entity\Coffee,
        Application\API\Canonicals\Entity\CoffeeView,
        Application\API\Repositories\Base\IRepository,
        Application\API\Repositories\Base\Repository;

    class CoffeeRepository implements ICoffeeRepository {
        
        /**
         * @var EntityManager 
         */
        protected $em;
        
        /**
         * @var IRepository
         */
        protected $coffeeRepo;
        
        /**
         * @var IRepository
         */
        protected $coffeeViewRepo;
        
        public function __construct(EntityManager $em) {
            $this->em = $em;
            $this->coffeeRepo = new Repository($em, new EntityRepository($em, new ClassMetadata(get_class(new Coffee()))));
            $this->coffeeViewRepo = new Repository($em, new EntityRepository($em, new ClassMetadata(get_class(new CoffeeView()))));
        }

        public function getNewCoffeeCode() {
            do {
                $number = str_shuffle("3456789");
                $coffeeCode = "L";
                $numberLen = 2;
                
                for($i = 0, $l = strlen($number) - 1; $i < $numberLen; $i ++) {
                    $coffeeCode .= strtoupper($number{mt_rand(0, $l)});
                }

            } while ($this->coffeeRepo->count(['coffeecode' => $coffeeCode]) > 0);
            
            return $coffeeCode;
        }
        
        public function addCoffee(Coffee $coffee) {
            $coffee->setCoffeecode($this->getNewCoffeeCode());
            $this->coffeeRepo->add($coffee);
        }

        public function updateCoffee(Coffee $coffee) {
            $this->coffeeRepo->update($coffee);
        }

        public function addOrUpdateCoffee(Coffee $coffee) {
            if ($coffee->getCoffeekey() != null) {
                $this->addCoffee($coffee);
            } else {
                $this->updateCoffee($coffee);
            }
        }

        public function incrementCoffee($coffeeKey) {
            $coffee = $this->coffeeRepo->fetch($coffeeKey);
            
            if ($coffee == null) {
                throw new \Exception("Could not find matching item");
            } else {
                $coffee->setAvailableamount($coffee->getAvailableamount() + 1);
                $this->coffeeRepo->update($coffee);
                return $this->coffeeViewRepo->findOneBy(['coffeekey' => $coffeeKey]);
            }
        }
        
        public function decrementCoffee($coffeeKey) {
            $coffee = $this->coffeeRepo->fetch($coffeeKey);
            
            if ($coffee == null) {
                throw new \Exception("Could not find matching item");
            } else if ($coffee->getAvailableamount() <= 1) {
                throw new \Exception("Cannot Decrement further");
            } else {
                $coffee->setAvailableamount($coffee->getAvailableamount() - 1);
                $this->coffeeRepo->update($coffee);
                return $this->coffeeViewRepo->findOneBy(['coffeekey' => $coffeeKey]);
            }
        }
        
        public function toggleActive($coffeeKey) {
            $coffee = $this->coffeeRepo->fetch($coffeeKey);
            
            if ($coffee == null) {
                throw new \Exception("Could not find matching item");
            } else {
                $coffee->setActive($coffee->getActive() == 0 ? 1 : 0);
                $this->updateCoffee($coffee);
                return $this->coffeeViewRepo->findOneBy(['coffeekey' => $coffeeKey]);
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
            $sheet->SetCellValue("T1", 'Remaining Amt');
            
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
                $sheet->SetCellValue("T$row", $c->getRemainingamount());
            }
            
            return $objPHPExcel;
        }
        
        public function find($coffeeKey) {
            return $this->coffeeViewRepo->findOneBy(['coffeekey' => $coffeeKey]);
        }

        public function findAll() {
            return $this->coffeeViewRepo->fetchAll();
        }
        public function findAllActive() {
            return $this->coffeeViewRepo->findBy(['active' => 1]);
        }
    }
}
