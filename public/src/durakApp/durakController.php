<?php
namespace durak;

class durakController
{
    public $csvData;
    public $csvHtml;
    public $player;
    public $gamesCount;
    public $playerPresent;
    public $timeLastGameSubmitted;
    public $amountHidden;
    public $efaGettho = ["Gero", "Don", "Toni", "Flo", "Marco"];

    public function init() {
        $this->readCsv();
        $this->getPlayer();
        $this->setPlayerPresent();
        $this->setAmountHidden();
        $this->getGamesAmount();
        $this->renderTable();
        if($_SESSION != null && array_key_exists('timestamp', $_SESSION)) {
            $this->timeLastGameSubmitted = $_SESSION['timestamp'];
        }
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
            if ($firstRow[$i] != '') {
                $this->player[] = $firstRow[$i];
            }
        }
    }
    public function renderTable() {
        //Render CSV table
        for ($i = count($this->csvData) - 10; $i < count($this->csvData);  $i++) {
            $row = explode(";", $this->csvData[$i][0]);
            $this->csvHtml .= "<tr>";
            for ($j = 0; $j < count($row); $j++) {
                $hidden = '';
                $loss = '';
                $draw = '';
                if($row[$j] == '') $row[$j] = ' ';
                if($j >= $this->amountHidden + 2) break;
                if($row[$j] == 1) $loss = 'text-danger big-bold';
                if($row[$j] == 2) $draw = 'text-info big-bold';
                $this->csvHtml .= "<td class='$loss $draw'>$row[$j]</td>";
            }
            $this->csvHtml .= "</tr>";
        }
    }
    public function handleFormData() {
        if(array_key_exists('submitGame', $_SESSION['postdata'])) {
            $this->submitGame($_SESSION['postdata'], false);
        } elseif(array_key_exists('deleteGame', $_SESSION['postdata'])) {
            $this->deleteGame();
        } elseif(array_key_exists('playerDraw', $_SESSION['postdata'])) {
            $this->submitGame($_SESSION['postdata'], true);
        } elseif(array_key_exists('playerPresent', $_SESSION['postdata'])) {
            $_SESSION['playerPresent'] = $_SESSION['postdata']['playerPresent'];
            $this->setPlayerPresent();
        } elseif(array_key_exists('amountHidden', $_SESSION['postdata'])) {
            $_SESSION['amountHidden'] = $_SESSION['postdata']['amountHidden'];
            if($_SESSION['amountHidden'] != '100') {
                $_SESSION['playerPresent'] = $this->efaGettho;
                $this->setPlayerPresent();
            } else {
                $_SESSION['playerPresent'] = $this->player;
            }
            $this->setAmountHidden();
        }
    }
    public function setAmountHidden() {
        if(isset($_SESSION['amountHidden'])) {
            $this->amountHidden = $_SESSION['amountHidden'];
        } else {
            $this->amountHidden = 100;
        }
    }
    public function submitGame($postData, $isDraw) {
        $file = fopen('../statistics/durak/durak.csv','a');
        //Add game-number
        $row[0] = date ("j.n");
        //Add date
        $this->gamesCount++;
        $row[1] = $this->gamesCount;
        foreach ($this->player as $key => $player) {
            $found = false;
            foreach ($this->playerPresent as $playerPresent) {
                if ($playerPresent == $player) {
                    if($isDraw) {
                        //write row
                        if($postData['playerDraw'][0] == $playerPresent || $postData['playerDraw'][1] == $playerPresent) {
                            $row[] = "2";
                        } else {
                            $row[] = "0";
                        }
                    } else {
                        if ($player == $postData['submitGame']) {
                            $row[] = "1";
                        } else {
                            $row[] = "0";
                        }
                    }
                    $found = true;
                }
            }
            if (!$found) {
                $row[] = '';
            }
        }
        //Write to CSV
        fputcsv($file, $row , ";");

        //set timestamp
        $_SESSION['timestamp'] .= "<div>Nr. " . $this->gamesCount . ": " . date("l d.m.y\ H:i:s") . "</div>";
    }
    public function deleteGame() {
        $path = '../statistics/durak/durak.csv';

        //Can't delete last game if not from today
        $lastrow = end($this->csvData)[0];
        $lastrow = explode(";", $lastrow);
        if($lastrow[0] !== date ("j.n")) {
            return;
        }
        //Delete last row
        $this->trim_lines($path, 1);
    }
    public function trim_lines($path, $max) {
        // Read the lines into an array
        $lines = file($path);
        // Setup counter for loop
        $counter = 0;
        while($counter < $max) {
            // array_pop removes the last element from an array
            array_pop($lines);
            // Increment the counter
            $counter++;
        }  // End loop
        // Write the trimmed lines to the file
        file_put_contents($path, implode('', $lines));
    }

    public function setPlayerPresent()
    {
        if(isset($_SESSION['playerPresent'])) {
            $this->playerPresent = $_SESSION['playerPresent'];
        } else {
            $this->playerPresent = $this->player;
        }
    }
}
