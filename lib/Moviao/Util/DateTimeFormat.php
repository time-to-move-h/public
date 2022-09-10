<?php
declare(strict_types=1);
namespace Moviao\Util;
use DateTime;

/**
 * DateTimeFormat
 * Format Date Time with timezone
 *
 * @author MoviaoOne
 */
class DateTimeFormat {
    
    public function parseDateTime(string $sdatetime, string $timezone): ?DateTime {
        // TimeZone                         
        if ($timezone == null || strlen($timezone) <= 0) {
            return null;
        }
        // Date Time TimeZone Formatter
        $tmz = new \DateTimeZone($timezone); 

        if (strlen($sdatetime) === 10) {
            $date1 = DateTime::createFromFormat('Y-m-d', $sdatetime, $tmz);
            $date1->setTime(0,0);
        } else {
            $date1 = DateTime::createFromFormat('Y-m-d H:i', $sdatetime, $tmz);
        }

        if ($date1 === false) {
            return null;
        }
        return $date1;     
    }

    public static function currentDateTime() : \DateTime {
        // Date Time TimeZone UTC
        $tmz = new \DateTimeZone('UTC');
        $now = new \DateTime('now',$tmz);
        return $now;
    }
    
    public function isInferiorNow(?DateTime $date, string $timezone, bool $allday) : bool {

        if (is_null($date)) {
            return true;
        }

        $tmz = new \DateTimeZone($timezone);
        $dateNow = new \DateTime("now", $tmz);

        if ($allday === true) {
            $dateNow->setTime(0,0);
        }

        if ($date < $dateNow) {              
            return true;
        } else {              
            return false;
        }                
    }   

    public function formatShortDate(?DateTime $date) : ?string {
        if (is_null($date)) {
            return '';
        }
        return $date->format('Y-m-d H:i');
    }

    public function formatDate(DateTime $date_start,?DateTime $date_end,?string $lang,bool $allday,bool $short) : ?string {

        if (empty($date_start)) {
            return null;
        }

        $result = null;        
        $day = ($date_start->format('d'));
        $dayWeek = (int)($date_start->format('w'));
        $month = (int)($date_start->format('n'));
        $hours = $date_start->format($this->getHourFormat($lang));
        $year = $date_start->format('Y');
        $dayWeekLetter = $this->getDay2($dayWeek,$lang);
        $resultLetter = $this->getMonth($month,$lang);

        if ($allday) {
            $hours = '';
        }

        //exit( var_dump($date_end) );

        // Date End
        $datend_formatted = '';
        $day2 = '';
        $month2 = 0;
        if (null !== $date_end) {
            //exit( var_dump($date_start) . " - " . var_dump($date_end) );
            //exit("xxx : " . $date_end->diff($date_start)->d);
            $diff = $date_end->diff($date_start)->d;
            $hours2 = $date_end->format($this->getHourFormat($lang));

            //exit( var_dump($diff) );

            if ($diff <= 0) {
                $datend_formatted = "- $hours2";
            } else {
                $day2 = ($date_end->format('d'));
                $dayWeek2 = (int)($date_end->format('w'));
                $month2 = (int)($date_end->format('n'));
                $year2 = $date_end->format('Y');
                $dayWeekLetter2 = $this->getDay2($dayWeek2,$lang);
                $resultLetter2 = $this->getMonth($month2,$lang);

                if ($allday) {
                    $hours2 = '';
                }

                $datend_formatted = " - $dayWeekLetter2, $day2  $resultLetter2  $year2 $hours2";
                //exit( var_dump($datend_formatted) );
            }
        }

        if ($short === true) {
            $shortMonth = $this->getMonth2($month,$lang);
            $shortMonth2 = $this->getMonth2($month2,$lang);

            if (! empty($day2)) {
                $day2 = "- " . $day2;
            }

            $result = trim("$day $shortMonth $day2 $shortMonth2");
        } else {
            $result = "$dayWeekLetter, $day $resultLetter $year $hours $datend_formatted";
        }

        return $result;
    }

    private function getDay(int $day,?string $lang) : string {
        $result = null;

        if ("fr-BE" === $lang) {
            switch ($day) {
                case 0:
                    $result = "dimanche";
                    break;
                case 1:
                    $result = "lundi";
                    break;
                case 2:
                    $result = "mardi";
                    break;
                case 3:
                    $result = "mercredi";
                    break;
                case 4:
                    $result = "jeudi";
                    break;
                case 5:
                    $result = "vendredi";
                    break;
                case 6:
                    $result = "samedi";
                    break;
            }
        } else if ("es-ES" === $lang) {
            switch ($day) {
                case 0:
                    $result = "domingo";
                    break;
                case 1:
                    $result = "lunes";
                    break;
                case 2:
                    $result = "martes";
                    break;
                case 3:
                    $result = "miercoles";
                    break;
                case 4:
                    $result = "jueves";
                    break;
                case 5:
                    $result = "viernes";
                    break;
                case 6:
                    $result = "sabado";
                    break;
            }
        } else {
            switch ($day) {
                case 0:
                    $result = "sunday";
                    break;
                case 1:
                    $result = "monday";
                    break;
                case 2:
                    $result = "tuesday";
                    break;
                case 3:
                    $result = "wednesday";
                    break;
                case 4:
                    $result = "thursday";
                    break;
                case 5:
                    $result = "friday";
                    break;
                case 6:
                    $result = "saturday";
                    break;
            }
        }

        return $result;
    }

    private function getDay2(int $day,?string $lang) : string {
        $result = null;

        if ("fr-BE" === $lang) {
            switch ($day) {
                case 0:
                    $result = "dim";
                    break;
                case 1:
                    $result = "lun";
                    break;
                case 2:
                    $result = "mar";
                    break;
                case 3:
                    $result = "mer";
                    break;
                case 4:
                    $result = "jeu";
                    break;
                case 5:
                    $result = "ven";
                    break;
                case 6:
                    $result = "sam";
                    break;
            }
        } else if ("es-ES" === $lang) {
            switch ($day) {
                case 0:
                    $result = "dom";
                    break;
                case 1:
                    $result = "lun";
                    break;
                case 2:
                    $result = "mar";
                    break;
                case 3:
                    $result = "mie";
                    break;
                case 4:
                    $result = "jue";
                    break;
                case 5:
                    $result = "vie";
                    break;
                case 6:
                    $result = "sab";
                    break;
            }
        } else {
            switch ($day) {
                case 0:
                    $result = "sun";
                    break;
                case 1:
                    $result = "mon";
                    break;
                case 2:
                    $result = "tue";
                    break;
                case 3:
                    $result = "wed";
                    break;
                case 4:
                    $result = "thu";
                    break;
                case 5:
                    $result = "fri";
                    break;
                case 6:
                    $result = "sat";
                    break;
            }
        }

        return $result;
    }

    private function getMonth(int $month,?string $lang) {
        $result = null;                
        if ("fr-BE" === $lang) {
         switch($month) {
            case 1: 
                $result = 'janvier';
                break;
            case 2: 
                $result = 'février';
                break;
            case 3: 
                $result = 'mars';
                break;
            case 4: 
                $result = 'avril';
                break;
            case 5: 
                $result = 'mai';
                break;
            case 6: 
                $result = 'juin';
                break;
            case 7: 
                $result = 'juillet';
                break;
            case 8: 
                $result = 'août';
                break;
            case 9: 
                $result = 'septembre';
                break;
            case 10: 
                $result = 'octobre';
                break;
            case 11: 
                $result = 'novembre';
                break;
            case 12: 
                $result = 'decembre';
                break;
            default: 
                $result =''; 
                break;
          } 
        } else if ("es-ES" === $lang) {            
            switch($month) {
                case 1: 
                    $result = 'enero';
                    break;
                case 2: 
                    $result = 'febrero';
                    break;
                case 3: 
                    $result = 'marso';
                    break;
                case 4: 
                    $result = 'abril';
                    break;
                case 5: 
                    $result = 'mayo';
                    break;
                case 6: 
                    $result = 'junio';
                    break;
                case 7: 
                    $result = 'julio';
                    break;
                case 8: 
                    $result = 'agosto';
                    break;
                case 9: 
                    $result = 'septiembre';
                    break;
                case 10: 
                    $result = 'octubre';
                    break;
                case 11: 
                    $result = 'noviembre';
                    break;
                case 12: 
                    $result = 'diciembre';
                    break;
                default: 
                    $result =''; 
                    break;
              }
            
        } else {
          switch($month) {
            case 1: 
                $result = 'january';
                break;
            case 2: 
                $result = 'february';
                break;
            case 3: 
                $result = 'march';
                break;
            case 4: 
                $result = 'april';
                break;
            case 5: 
                $result = 'may';
                break;
            case 6: 
                $result = 'june';
                break;
            case 7: 
                $result = 'july';
                break;
            case 8: 
                $result = 'august';
                break;
            case 9: 
                $result = 'september';
                break;
            case 10: 
                $result = 'october';
                break;
            case 11: 
                $result = 'november';
                break;
            case 12: 
                $result = 'december';
                break;
            default: 
                $result =''; 
                break;
          }          
        }        
        return $result;
    }

    private function getMonth2(int $month,?string $lang) {
        $result = null;
        if ("fr-BE" === $lang) {
            switch($month) {
                case 1:
                    $result = 'JAN';
                    break;
                case 2:
                    $result = 'FEV';
                    break;
                case 3:
                    $result = 'MAR';
                    break;
                case 4:
                    $result = 'AVR';
                    break;
                case 5:
                    $result = 'MAI';
                    break;
                case 6:
                    $result = 'JUN';
                    break;
                case 7:
                    $result = 'JUL';
                    break;
                case 8:
                    $result = 'AOÛ';
                    break;
                case 9:
                    $result = 'SEP';
                    break;
                case 10:
                    $result = 'OCT';
                    break;
                case 11:
                    $result = 'NOV';
                    break;
                case 12:
                    $result = 'DÉC';
                    break;
                default:
                    $result ='';
                    break;
            }
        } else if ("es-ES" === $lang) {
            switch($month) {
                case 1:
                    $result = 'ENE';
                    break;
                case 2:
                    $result = 'FEB';
                    break;
                case 3:
                    $result = 'MAR';
                    break;
                case 4:
                    $result = 'ABR';
                    break;
                case 5:
                    $result = 'MAY';
                    break;
                case 6:
                    $result = 'JUN';
                    break;
                case 7:
                    $result = 'JUL';
                    break;
                case 8:
                    $result = 'AGO';
                    break;
                case 9:
                    $result = 'SEP';
                    break;
                case 10:
                    $result = 'OCT';
                    break;
                case 11:
                    $result = 'NOV';
                    break;
                case 12:
                    $result = 'DIC';
                    break;
                default:
                    $result ='';
                    break;
            }

        } else {
            switch($month) {
                case 1:
                    $result = 'JAN';
                    break;
                case 2:
                    $result = 'FEB';
                    break;
                case 3:
                    $result = 'MAR';
                    break;
                case 4:
                    $result = 'APR';
                    break;
                case 5:
                    $result = 'MAY';
                    break;
                case 6:
                    $result = 'JUN';
                    break;
                case 7:
                    $result = 'JUL';
                    break;
                case 8:
                    $result = 'AUG';
                    break;
                case 9:
                    $result = 'SEP';
                    break;
                case 10:
                    $result = 'OCT';
                    break;
                case 11:
                    $result = 'NOV';
                    break;
                case 12:
                    $result = 'DEC';
                    break;
                default:
                    $result ='';
                    break;
            }
        }
        return $result;
    }

    private function getHourFormat(?string $lang) : string {
        if (! empty($lang) && mb_substr($lang,0,2) === 'fr') {
            $s = 'H:i';
        } else {
            $s = 'h:i a';
        }
        return $s;
    }

}