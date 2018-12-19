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
            //$basePayDate = $this->getLastDayOfMonth($month);
            


            //$line .= ' Base salary on: '.$this->printNameOfDay($basePayDate) .' '.$basePayDate . ', Extra on: '. $extras[$extraCounter];
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
            //echo "currentMonth:".$currentMonth;
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
            //echo $new_month.PHP_EOL;

        }

        /*for ($x = $currentMonth; $x < $currentMonth + 12; $x++) {
           // $tempday =  date('D', mktime(0, 0, 0, $x, 1, $currentYear));


          
           
           //echo  date('D-j-m-Y', mktime(0, 0, 0, $x, date("t", strtotime($date)), $currentYear)).PHP_EOL;
           
            if ($this->getLastDayOfMonth($x) == "Sun") {
                $weekday =  date('D-j-m-Y', mktime(0, 0, 0, $x, date("t", strtotime($date))-2, $currentYear));
              
            } else if ($this->getLastDayOfMonth($x) == "Sat") {
                $weekday =  date('D-j-m-Y', mktime(0, 0, 0, $x, date("t", strtotime($date))-1, $currentYear));
              
            }
            else {
                $weekday =  date('D-j-m-Y', mktime(0, 0, 0, $x, date("t", strtotime($date)), $currentYear));
            }



            //$months[] = date('Y-m', mktime(0, 0, 0, $x, 1, $currentYear));
            $months[] = $weekday;
            

            //$months['day'] = date('D', mktime(0, 0, 0, $x, 1, $currentYear));
        }*/


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
            //echo "currentMoneth:".$currentMonth;
        } else {
            $currentMonth = (int)date('m');
        }
        
       
        for ($x = $currentMonth; $x < $currentMonth + 12; $x++) {
            //$months[] = date('Y-m', mktime(0, 0, 0, $x, 1, $currentYear));
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
            //echo   date('D-j-m-Y', mktime(0, 0, 0, $x, 15, $currentYear)).PHP_EOL;
        }


        foreach ($extras as $extra) {
            echo $extra.PHP_EOL;
        }

        return $extras;
    }


    public function getCommandLineOptions() {
        $options = getopt("f:hp:");
        return $options['f'];
       
    }

}


    $salaries = new InviquaSalary();

//    $salaries->tests();
    $cmdDate = $salaries->getCommandLineOptions();
    
    echo 'Please define a starting Date in the format -f4 Digit year- 2 digit month- 2 digit day example: "-f2018-01-01"'.PHP_EOL;
    
    //echo $cmdDate;

    $salaries->writeCSV("payments.csv", $salaries->getNext12Months($date=$cmdDate), $salaries->getNext12Extras($date=$cmdDate));
    
    echo 'Output was written to file payments.csv';
    //$salaries->writeCSV("payments.csv", $salaries->getNext12Extras($date="2019-01-08"));

    //$salaries->getNext12Months();
    