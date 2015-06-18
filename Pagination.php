<?php

namespace Helpers;

/**
 * 
 *
 * @author Apssouza
 */
class Pagination {

    private $currentPage;
    private $totalOfPage;
    private $totalView;
    private $template;
    

    public function setCurrentPage($page) {
        $this->currentPage = $page ? $page : 1 ;
    }

    public function setTotalPage($total) {
        $this->totalOfPage = $total;
    }

    public function setTotalView($total) {
        $this->totalView = $total;
    }

    public function setTemplate($template) {
        $this->template = $template;
    }
    
    public function getCurrentPage() {
        return $this->currentPage;
    }
    
    public function getTotalView() {
        return $this->totalView;
    }

    private function getTemplate() {
        if(!$this->template){
            return '<li class="{classactive}"><a href="?page={page}">{page}</a></li>';
        }
        return $this->template;
    }

    public function render() {
        $total = ceil($this->totalOfPage / $this->totalView);
        $pagination = '';
        for ($i = 1; $i <= $total; $i++) {
            $active = $i == $this->currentPage ? 'active' : '';
            $pagination .= str_replace(array('{classactive}','{page}' ), array($active, $i), $this->getTemplate());
        }
        return $pagination;
    }


}
