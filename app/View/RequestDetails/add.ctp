<h2>Add Request</h2>

<?php
     //debug($transportation_id_list);
     echo $this->Form->create('RequestDetail');
     echo $this->Form->hidden('RequestDetail.user_id', array('default' => $login_user_id));
     echo $this->Form->input('RequestDetail.date');
     echo $this->Form->input('RequestDetail.client');
     echo $this->Form->input('RequestDetail.transportation_id', array('options' => $transportation_id_list));
     echo $this->Form->input('RequestDetail.from_station');
     echo $this->Form->input('RequestDetail.to_station');
     echo $this->Form->input('RequestDetail.cost');
     echo $this->Form->input('RequestDetail.oneway_or_round', array(
         'type' => 'select',
         'options' => $oneway_or_round
     ));
     echo $this->Form->input('RequestDetail.overview');
     echo $this->Form->end('Save');

     //$date = $this->request->data['RequestDetail']['date']['year'].'-'.$this->request->data['RequestDetail']['date']['month'];

 ?>

 <button type="button" onclick="history.back()">キャンセル</button>
