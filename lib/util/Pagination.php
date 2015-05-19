<?php

namespace lib\util;

class Pagination {

    public static function paginate($objects, $rows = 50) {
        $lastIndex = 0;
        $lastPart = array();
        $pages = array();
        do {
            $lastPart = array_slice($objects, $lastIndex * $rows, $rows);
            if (!empty($lastPart)) {
                array_push($pages, $lastPart);
                $lastIndex++;
            }
        } while (count($lastPart) == $rows);
        return $pages;
    }

}
