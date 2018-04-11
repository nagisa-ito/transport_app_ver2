<h2>Add users</h2>

<?php
    echo $this->Form->create('User');
    echo $this->Form->input('username');
    echo $this->Form->input('password');
    echo $this->Form->input('yourname');
    echo $this->Form->input('department_id', array('options' => $department_id_list));
    echo $this->Form->end('save');
?>

<button type="button" onclick="history.back()">キャンセル</button>
