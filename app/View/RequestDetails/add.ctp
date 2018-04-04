<h2>Add Request</h2>

<?php echo $select_user_id; ?>

<?php 
 echo $this->Form->create('RequestDetails');
 echo $this->Form->hidden('select_user_id', ['value' => $select_user_id]);
 echo $this->Form->input('date');
 echo $this->Form->input('client');
 echo $this->Form->input('transportation_id');
 echo $this->Form->input('from_station');
 echo $this->Form->input('to_station');
 echo $this->Form->input('cost');
 echo $this->Form->input('oneway_or_round');
 echo $this->Form->input('overview');
 echo $this->Form->end('Save');
 ?>