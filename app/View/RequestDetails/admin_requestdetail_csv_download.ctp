<?php
    $this->Csv->addRow($head);
    foreach($data as $line) {
        $this->Csv->addRow($line);
    }
    $this->Csv->setFilename($filename);
    echo $this->Csv->render();
