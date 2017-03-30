<?php

namespace Application\API\Canonicals\Response {
    class SearchResponse extends Response {
        public $total = 0;
        public $totalpages = 0;
        public $page = 0;
        public $pagesize = 0;
        public $items;
    }

}
