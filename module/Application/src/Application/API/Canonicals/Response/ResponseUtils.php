<?php

namespace Application\API\Canonicals\Response {

    class ResponseUtils {
        public static function createExceptionResponse(\Exception $ex) {
            $response = new Response();
            $response->success = false;
            array_push($response->errors, $ex->getMessage());
            return $response;
        }
        
        public static function response(array $errors = array(), array $warnings = array()) {
            $response = new Response();
            $response->errors = $errors;
            $response->warnings = $warnings;
            $response->success = count($errors) == 0;
            return $response;
        }
        
        public static function responseItem($item, array $errors = array(), array $warnings = array()) {
            $response = new ResponseItem();
            $response->errors = $errors;
            $response->warnings = $warnings;
            $response->success = count($errors) == 0;
            $response->item = $response->success ? $item : null;
            return $response;
        }
        
        public static function responseList(array $items, array $errors = array(), array $warnings = array()) {
            $response = new ResponseList();
            $response->errors = $errors;
            $response->warnings = $warnings;
            $response->success = count($errors) == 0;
            $response->items = $response->success ? $items : array();
            return $response;
        }
        
        public static function createSearchResponse($total, $items, $page, $pageSize, array $errors = array(), array $warnings = array()) {
            $response = new SearchResponse();
            $response->errors = $errors;
            $response->warnings = $warnings;
            $response->success = count($errors) == 0;
            $response->items = $response->success ? $items : array();
            $response->total = $total;
            
            $response->pagesize   = $pageSize;
            $response->totalpages = ceil($total / $pageSize);
            $response->page       = $page;
            
            return $response;
        }
        
    }

}
