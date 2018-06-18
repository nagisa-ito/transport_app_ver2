<?php
$this->Csv->addRow($header);
foreach($data as $line) {
    $this->Csv->addRow($line);
}
$this->Csv->setFilename($filename);
echo $this->Csv->render();
