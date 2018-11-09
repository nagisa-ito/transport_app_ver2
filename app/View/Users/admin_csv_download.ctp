<?php
$this->Csv->addRow($top);
$this->Csv->addRow($total_for_appointment);
$this->Csv->addRow($total_for_go_work);
$this->Csv->addRow(array());

foreach($sorted_data as $key => $line) {
    array_unshift($line, $header[$key]);
    $this->Csv->addRow($line);
}
$this->Csv->setFilename($filename);
echo $this->Csv->render();
