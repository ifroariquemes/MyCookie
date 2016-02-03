<?php

namespace lib\util;

use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Tools\Pagination\Paginator;

class Pagination
{

    public static function paginate(QueryBuilder $query, $curPage = 1, $records = 25)
    {
        $paginator = new Paginator($query);
        $paginator
                ->getQuery()
                ->setFirstResult($records * ($curPage - 1))
                ->setMaxResults($records);
        return $paginator;
    }
    
    public static function getPages(Paginator $data) {
        return ceil($data->count() / $data->getQuery()->getMaxResults());
    }
}