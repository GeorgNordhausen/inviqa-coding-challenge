<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
/*
    inviqua Burroughs Test
    Georg Nordhausen Dec 2018
*/

class InviquaSalary
{
    private function getLastDayOfMonth($date) {

        $lastDay = date("t", strtotime($date));
        $nameOfDay = date('D', strtotime($date));

        return $lastDay;
    }

    private function printNameOfDay($date) {

        return date('D', strtotime($date));
    }


    public function tests() {

        echo $this->getLastDayOfMonth("2018-11-23");

        $this->printNameOfDay("2018-12-08");

        
    }

    public function writeCSV($filename, $months, $extras) {

        $file = fopen($filename,"w");

        $extraCounter = 0;

        foreach ($months as $month)
        {

            $line = $month;
            
            $line = ' Base salary on: '.$month . ', Bonus on: '. $extras[$extraCounter];
            fputcsv($file,explode(',',$line));
            $extraCounter++;
        }

        fclose($file);
    }

    public function getNext12Months($date ="") {

        $months = array();

        if ($date !== "") {
            $currentMonth = date("m",strtotime($date));
            $currentYear = date("Y", strtotime($date));
            
        } else {
            $currentMonth = (int)date('m');
        }
        
       
        $this_month = mktime(0, 0, 0, date('m'), 1, date('Y'));
        for ($i = 0; $i < 12; ++$i) {
            echo date(' M Y', strtotime($i.' month', $this_month)) .PHP_EOL;
            $tempDay = date('t', strtotime($i.' month', $this_month));

            $new_day =  date('D', strtotime('last day of'. $i. ' month', $this_month));
            $new_month = date('D j M Y', strtotime('last day of'. $i. ' month', $this_month));
            if ($new_day == "Sun") {
                $new_month = date('D j M Y', strtotime($new_month.' -2 day'));
            } else if ($new_day == "Sat") {
                $new_month = date('D j M Y', strtotime($new_month.' -1 day' ));
            }
            
            $months[] = $new_month;
            
        foreach ($months as $month) {
            echo $month.PHP_EOL;
        }

        return $months;
    }

    public function getNext12Extras($date ="") {

        $extras = array();

        if ($date !== "") {
            $currentMonth = date("m",strtotime($date));
            $currentYear = date("Y", strtotime($date));
            
        } else {
            $currentMonth = (int)date('m');
        }
        
       
        for ($x = $currentMonth; $x < $currentMonth + 12; $x++) {
            
            $tempday =  date('D', mktime(0, 0, 0, $x, 15, $currentYear));
            
            if ($tempday == "Sun") {
                $weekday =  date('D-j-m-Y', mktime(0, 0, 0, $x, 15+3, $currentYear));
            } else if ($tempday == "Sat") {
                $weekday =  date('D-j-m-Y', mktime(0, 0, 0, $x, 15+4, $currentYear));
            }
            else {
                $weekday =  date('D-j-m-Y', mktime(0, 0, 0, $x, 15, $currentYear));
            }
            $extras[] = $weekday;
            
        }


        foreach ($extras as $extra) {
            echo $extra.PHP_EOL;
        }

        return $extras;
    }


    public function getCommandLineOptions() {
       $options = getopt("f:hp:");
      // $options = $argv; 
       
       return $options['f'];
      // return $argv;
       
    }

}

    $salaries = new InviquaSalary();

    $cmdDate = $salaries->getCommandLineOptions();
    
    echo 'Please define a starting Date in the format -f4 Digit year- 2 digit month- 2 digit day example: "-f2018-01-01"'.PHP_EOL;
    
    $salaries->writeCSV("payments.csv", $salaries->getNext12Months($date=$cmdDate), $salaries->getNext12Extras($date=$cmdDate));
    
    echo 'Output was written to file payments.csv';