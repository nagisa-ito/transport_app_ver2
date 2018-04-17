<html>
<body>

    <header><h2>Edit Request</h2></header>

    <div class="content row">
        <div class="col-sm-6 offset-sm-3">
            <div class="form_contents">
                <?php
                     echo $this->Form->create('RequestDetail', ['class' => 'form_inline']);
                     echo $this->Form->hidden('RequestDetail.user_id', array('default' => $login_user_id));
                     echo $this->Form->input('RequestDetail.date',  ['label' => ['text' => '日付']]);
                     echo $this->Form->input('RequestDetail.client', ['label' => ['text' => 'クライアント名'], 'class' => 'form-control']);
                     echo $this->Form->input('RequestDetail.transportation_id', ['options' => $transportation_id_list, 'label' => ['text' => '交通手段']]);
                     echo $this->Form->input('RequestDetail.from_station', ['class' => 'form-control', 'label' => '区間', 'placeholder' => '乗車駅']);
                     echo $this->Form->input('RequestDetail.to_station', ['class' => 'form-control', 'label' => '', 'placeholder' => '降車駅']);
                     echo $this->Form->input('RequestDetail.cost', ['class' => 'form-control', 'label' => '費用']);
                     echo $this->Form->input('RequestDetail.oneway_or_round', array(
                         'type' => 'select',
                         'options' => $oneway_or_round,
                         'label' => '往復or片道'
                     ));
                     echo $this->Form->input('RequestDetail.overview', ['class' => 'form-control', 'label' => '備考']);
                ?>
                <div class="text-right">
                <?php echo $this->Form->button(__('Edit Request'), ['class' => 'btn btn-danger']);
                      echo $this->Form->end();
                ?>
                <button type="button" onclick="history.back()" class="btn page-link text-dark d-inline-block">Cancel</button>

            </div>
        </div>
    </div>

    <footer class="footer"></footer>

</body>
    <?php echo $this->Html->css('mystyle'); ?>
</html>
