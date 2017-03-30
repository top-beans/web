<?php

namespace Application\API\Canonicals\Dto {
    use JMS\Serializer\Annotation\Type;
    use JMS\Serializer\Annotation\SerializedName;
    use JMS\Serializer\Annotation\AccessType;
    
    /**
     * @AccessType("public_method")
     */
    class SearchParams { 
        
        /**
         * @Type("integer")
         */
        private $page;
        
        /**
         * @Type("integer")
         */
        private $pagesize;
        
        /**
         * @Type("array")
         */
        private $criteria;
        
        /**
         * @Type("array")
         */
        private $orderby;
        
        public function setPage($val) {
            $this->page = $val;
        }
        
        public function setPagesize($val) {
            return $this->pagesize = $val;
        }
        
        public function setCriteria($val) {
            return $this->criteria = $val;
        }
        
        public function setOrderby($val) {
            return $this->orderby = $val;
        }
        
        public function getPage() {
            return $this->page;
        }
        
        public function getPagesize() {
            return $this->pagesize;
        }
        
        public function getCriteria() {
            $list = [];
            if ($this->criteria != null) {
                foreach ($this->criteria as $key => $val) {
                    $list[key($val)] = $val[key($val)];
                }
            }
            return $list;
        }
        
        public function getOrderby() {
            $list = [];
            if ($this->orderby != null) {
                foreach ($this->orderby as $key => $val) {
                    $list[key($val)] = $val[key($val)];
                }
            }
            return $list;
        }
    }
}
