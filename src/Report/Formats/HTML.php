<?php namespace LeadMax\TrackYourStats\Report\Formats;

/**
 * Author: Dean
 * Email: dwm348@gmail.com
 * Date: 10/23/2017
 * Time: 11:33 AM
 */
class HTML implements Format
{

    public $lastRowStatic;

    public $printTheseArrayKeys;

    public function __construct($lastRowStatic = false, $printTheseArrayKeys = [])
    {
        $this->lastRowStatic = $lastRowStatic;

        $this->printTheseArrayKeys = $printTheseArrayKeys;
    }

    public function resetArrayKeys($array)
    {
        $temp = [];
        foreach ($array as $item) {
            $temp[] = $item;
        }

        return $temp;
    }

    public function output($report)
    {
	    $params = "";
	    if(isset($_GET['d_from']) && isset($_GET['d_to']) && isset($_GET['dateSelect']) ) {
		    $params = "&d_from=" . $_GET['d_from'] . "&d_to=" . $_GET['d_to'] . "&dateSelect=" . $_GET["dateSelect"];
	    }

        $report = $this->resetArrayKeys($report);

        foreach ($report as $key => $row) {
            if ($this->lastRowStatic && $key == count($report) - 1) {
                echo "<tr class='static'>";
            } else {
                echo "<tr>";
            }

            if (empty($this->printTheseArrayKeys)) {
                foreach ($row as $item => $val) {

	                if($item == "conversions" && $val > 0 && $row["sub"] != "TOTAL") {
		                echo "<td><a href='/report/sub/conversions?subid={$row["sub"]}{$params}'>{$val}</a></td>";
	                } else {
		                echo "<td>{$val}</td>";
	                }
                }
            } else {
                foreach ($this->printTheseArrayKeys as $toPrint) {
                    if (isset($row[$toPrint])) {
                        echo "<td>$row[$toPrint]</td>";
                    }
                }
            }
            echo "</tr>";
        }
    }
}