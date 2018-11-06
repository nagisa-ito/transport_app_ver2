<?php
    $this->Csv->addRow($head);
    foreach($requests as $request) {
        $this->Csv->addRow($request);
    }
    $this->Csv->setFilename($filename);
    $this->Csv->addRow(array());
    $this->Csv->addRow($cost_head);
    $this->Csv->addRow($total_costs);
    echo $this->Csv->render();
