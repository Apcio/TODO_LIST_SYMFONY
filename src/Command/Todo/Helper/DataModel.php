<?php
namespace App\Command\Todo\Helper;

use DateTime;
use stdClass;

class DataModel {
    private $filePath;
    private $list;

    private function initialize() {
        if(!file_exists($this->filePath)) {
            file_put_contents($this->filePath, json_encode(array()));
        }
        $data = file_get_contents($this->filePath);
        if(empty($data)) {
            $this->list = array();
        } else {
            $this->list = json_decode($data, true, 512, JSON_OBJECT_AS_ARRAY);
        }

        if(!is_array($this->list)) {
            $this->list = array();
        }
    }

    private function getNextNumber() {
        $max = 0;
        foreach($this->list as $v) {
            if(array_key_exists("nr", $v)) {
                if($v["nr"] > $max) {
                    $max = $v["nr"];
                }
            }
        }
        return ++$max;
    }

    private function saveList() {
        $json = json_encode($this->list, JSON_OBJECT_AS_ARRAY | JSON_UNESCAPED_UNICODE);
        file_put_contents($this->filePath, $json);
    }

    private function uSortByDateCallback($a, $b) {
        $dateA = null;
        $dateB = null;
        $nrA = 0;
        $nrB = 0;

        if(array_key_exists("data", $a)) {
            $dateA = DateTime::createFromFormat("Y-m-d", $a["data"]); 
        }

        if(array_key_exists("data", $b)) {
            $dateB = DateTime::createFromFormat("Y-m-d", $b["data"]); 
        }

        if(array_key_exists("nr", $a)) {
            $nrA = intval($a["nr"]); 
        }

        if(array_key_exists("nr", $b)) {
            $nrB = intval($b["nr"]); 
        }

        if($dateA === $dateB) {
            if($nrA === $nrB) {
                return 0;
            } else {
                return ($nrA < $nrB ? -1 : 1);
            }
        }

        if($dateA === null) return 1;
        if($dateB === null) return -1;

        return ($dateA < $dateB ? -1 : 1);
    }

    private function uSortByNameCallback($a, $b) {
        $nameA = null;
        $nameB = null;
        $nrA = 0;
        $nrB = 0;
        
        if(array_key_exists("nazwa", $a)) {
            $nameA = $a["nazwa"]; 
        }

        if(array_key_exists("nazwa", $b)) {
            $nameB = $b["nazwa"]; 
        }

        if(array_key_exists("nr", $a)) {
            $nrA = intval($a["nr"]); 
        }

        if(array_key_exists("nr", $b)) {
            $nrB = intval($b["nr"]); 
        }

        if((!isset($nameA) && !isset($nameB)) || (strnatcmp($nameA, $nameB) === 0)) {
            if($nrA === $nrB) {
                return 0;
            } else {
                return ($nrA < $nrB ? -1 : 1);
            }
        }

        if($nameA === null) return 1;
        if($nameB === null) return -1;

        return strnatcmp($nameA, $nameB);
    }

    public function __construct() {
        $this->filePath = __DIR__ . "/Data.json";
        $this->initialize();
    }

    public function addNewJob(string $name, string $desc, DateTime $date) {
        $job = array();
        $job["nr"] = $this->getNextNumber();
        $job["nazwa"] = $name;
        $job["opis"] = $desc;
        $job["data"] = $date->format("Y-m-d");
        $job["zrealizowane"] = "Nie";
        array_push($this->list, $job);
        $this->saveList();
    }

    public function getList(bool $onlyToday = false, bool $hideCompleted = false) {
        if($onlyToday === false && $hideCompleted === false) {
            return $this->list;
        }

        $list = array();
        foreach($this->list as $v) {
            if($onlyToday === true) {
                if(!array_key_exists("data", $v)) {
                    continue;
                }
                
                if( !($v["data"] === date('Y-m-d')) ) {
                    continue;
                }
            }

            if($hideCompleted === true) {
                if(!array_key_exists("zrealizowane", $v)) {
                    continue;
                }

                if($v["zrealizowane"] === "Tak") {
                    continue;
                }
            }

            array_push($list, $v);
        }

        return $list;
    }

    public function sortList(array $list = null, string $column = "nazwa") {
        if(!isset($list)) {
            return false;
        }

        if($column !== "data" && $column !== "nazwa") {
            $column = "nazwa";
        }

        switch($column) {
            case "nazwa" : usort($list, array($this, "uSortByNameCallback"));
                break;
            default: usort($list, array($this, "uSortByDateCallback"));
                break;
        }

        return $list;
    }

    public function getListForTable(array $list = null) {
        $tab = new stdClass();
        $tab->HeaderList = array("Nr", "Nazwa", "Opis", "Data", "Zrealizowane");
        $tab->Rows = array();

        if($list === null) return $tab;

        foreach($list as $v) {
            $row = array(
                array_key_exists("nr", $v) ? $v["nr"] : '',
                array_key_exists("nazwa", $v) ? $v["nazwa"] : '',
                array_key_exists("opis", $v) ? $v["opis"] : '',
                array_key_exists("data", $v) ? $v["data"] : '',
                array_key_exists("zrealizowane", $v) ? $v["zrealizowane"] : ''
            );
            array_push($tab->Rows, $row);
        }
        return $tab;
    }

    public function removeJob(int $no) {
        $index = null;
        foreach($this->list as $k => $v) {
            if(array_key_exists("nr", $v)) {
                if($v["nr"] === $no) {
                    $index = $k;
                    break;
                }
            }
        }
        if(is_int($index)) {
            array_splice($this->list, $index, 1);
            $this->saveList();
            return true;
        }
        return false;
    }

    public function jobComplete(int $no) {
        $index = null;
        foreach($this->list as $k => $v) {
            if(array_key_exists("nr", $v)) {
                if($v["nr"] === $no) {
                    $index = $k;
                    break;
                }
            }
        }
        if(is_int($index)) {
            $this->list[$index]["zrealizowane"] = "Tak";
            $this->saveList();
            return true;
        }
        return false;
    }
}
