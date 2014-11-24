<?php

namespace Helpers;

date_default_timezone_set('America/Sao_Paulo');

/**
 * Helper de datas
 *
 * @author Alexsandro
 */
class Date {

    /**
     * Retorna a data preparada para ser enviada ao MySQL no formato DATE ou DATETIME (se fornecida a hora) 
     * @param string $quando data no formato mm/dd/yyyy
     * @return string $quando data no formato yyyy-mm-dd
     */
    public static function getDateToMysql($quando) {
        if (empty($quando)) {
            return false;
        }
        $vetor = explode('/', $quando);
        $dia = floor($vetor[0]);
        $mes = floor($vetor[1]);
        $ano = floor(substr($vetor[2], 0, 4));

        if (strlen($vetor[2]) > 4) {
            $hora = floor(substr($vetor[2], 5, 2));
            $minuto = floor(substr($vetor[2], 8, 2));
            $segundo = floor(substr($vetor[2], 11, 2));
        } else {
            $hora = 0;
            $minuto = 0;
            $segundo = 0;
        }

        if (!checkdate($mes, $dia, $ano)) {
            return false;
        } else {
            return date("Y-m-d H:i:s", mktime($hora, $minuto, $segundo, $mes, $dia, $ano));
        }
    }

    public static function isValidDate($date) {
        if(empty($date))     
            return false;
        
        try {
            $dt = new \DateTime($date);
            return $dt;  
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Retorna a representa��o textual dos dias da semana ou o dia se passado o parametro
     * @param int $day representa��o numerica do dia da semana
     * @param boolean $complete acrescenta a -feira junto aos dias
     * @return string com o dia da semana 
     */
    public static function getDayWeek($day = null, $complete = false) {
        $feira = $complete ? '-feira' : '';
        $days = array('Domingo', 'Segunda' . $feira, 'Ter�a' . $feira, 'Quarta' . $feira, 'Quinta' . $feira, 'Sexta' . $feira, 'S�bado');
        if ($day) {
            return $days[$day];
        }
        return $days;
    }

    /**
     * Compara a diferencia entre duas datas 
     * @return DateInterval http://www.php.net/manual/en/class.dateinterval.php
     */
    public static function compareDateDiff(\DateTime $startDate, \DateTime $endDate) {
        return $startDate->diff($endDate);
    }

    /**
     * Formata um timestamp no formato brasileiro (somente data)
     * @param string $date
     */
    public static function getBrFormat($date, $withHour = false) {
        if (empty($date) || !self::isValidDate($date) || $date == '0000-00-00 00:00:00')
            return '';

        if ($withHour) {
            return date("d/m/Y H:i:s", strtotime($date));
        }
        return date("d/m/Y", strtotime($date));
    }

}

?>
