<header>
    <span class="badge badge-success">管理者</span>
    <h4 style="display: inline">申請を追加</h4>
</header>

<div class="text-center">
    <?php echo $this->Session->flash(); ?>
</div>

<div class="content row">
    <div class="col-sm-6 offset-sm-3">
        <div class="form_contents">
            <?php
                 echo $this->Form->create('RequestDetail', ['class' => 'form_inline']);
                 echo $this->Form->hidden('RequestDetail.user_id', array('default' => $login_user_id));
                 echo $this->Form->input('RequestDetail.date', ['type' => 'text', 'label' => ['text' => '日付'], 'id' => 'datepicker']);
                 echo $this->Form->input('RequestDetail.client',['label' => ['text' => 'クライアント名'], 'placeholder' => '訪問先が無い場合は空欄', 'class' => 'form-control']);
                 echo $this->Form->input('RequestDetail.transportation_id', array('options' => $transportation_id_list, 'label' => '交通手段'));
                 echo $this->Form->input('RequestDetail.from_station',  ['label' => ['text' => '利用区間'], 'placeholder' => '乗車駅', 'class' => 'form-control']);
                 echo $this->Form->input('RequestDetail.to_station', ['label' => ['text' => ''], 'placeholder' => '降車駅', 'class' => 'form-control']);
                 echo $this->Form->input('RequestDetail.cost', ['label' => ['text' => '費用'], 'class' => 'form-control']);
            ?>
            <div class="small caution">定期を考慮した金額を、片道の料金で記入してください。</div>
            <?php
                echo $this->Form->input('RequestDetail.oneway_or_round', array(
                    'type' => 'select',
                    'options' => $oneway_or_round,
                    'label' => '往復or片道'
                ));
                echo $this->Form->input('RequestDetail.overview', ['label' => ['text' => '備考'], 'class' => 'form-control']);
            ?>
            <div class="text-right">
            <?php echo $this->Form->button(__('続けて登録'), ['class' => 'btn btn-myset', 'name' => 'add_repeat'] ); ?>
            <?php echo $this->Form->button(__('登録'), ['class' => 'btn btn-myset', 'name' => 'add']);
                 echo $this->Form->end();
            ?>
             <button type="button" onclick="history.back()" class="btn page-link text-dark d-inline-block">Cancel</button>
            </div>
        </div>
    </div>
</div>

<footer class="footer"></footer>
<?php echo $this->Html->css('mystyle'); ?>
