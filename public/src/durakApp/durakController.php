<?php
namespace durak;

class durakController
{
    public $csvData;
    public $csvHtml;
    public $player;
    public $playerButtons;
    public $gamesCount;

    public function init() {
        $this->readCsv();
        $this->getPlayer();
        $this->renderHtml();
        $this->getGamesAmount();
    }
    public function readCsv() {
        $csv = array_map('str_getcsv', file('../statistics/durak/durak.csv'));
        $this->csvData = $csv;
    }
    public function getGamesAmount() {
        $lastrow = end($this->csvData)[0];
        $lastrow = explode(";", $lastrow);
        $this->gamesCount = $lastrow[1];
    }
    public function getPlayer() {
        $firstRow = explode(";", $this->csvData[0][0]);
        for ($i = 2; $i < count($firstRow);  $i++) {
            $this->player[] = $firstRow[$i];
        }
    }
    public function renderHtml() {
        //Render CSV Table
        for ($i = count($this->csvData) - 10; $i < count($this->csvData);  $i++) {
            $row = explode(";", $this->csvData[$i][0]);
            $this->csvHtml .= "<tr>";
            foreach ($row as $key => $field) {
                $hidden = '';
                if($key > 6) $hidden = 'hidden';
                $this->csvHtml .= "<td class='$hidden'>$field</td>";
            }
            $this->csvHtml .= "</tr>";
        }
        //Render Player-Buttons
        foreach ($this->player as $key => $player) {
            $hidden = '';
            if($key > 4) $hidden = 'hidden';
            $this->playerButtons .= "<th class='$hidden'><button name='submitGame' value='$player' type=\"submit\" class=\"btn btn-primary\">$player</button></th>";
        }
    }

    public function submitGame() {
        //TODO: Make this work
        return;
        $name = $_POST["submitGame"];
        $file = fopen('../statistics/durak/durak.csv','a');
        foreach ($this->player as $key => $col) {
            //Add date
            if ($key == 0) {
                $row[] = date ("j.n");
                continue;
            }

            //Add game-number
            if ($key == 1) {
                $row[] = $this->gamesCount++;
                continue;
            }

            //write row
            if($col == $name) {
                $row[] = "1";
            } else {
                $row[] = "0";
            }
        }
        //Write to CSV
        fputcsv($file, $row , ";");
    }
}