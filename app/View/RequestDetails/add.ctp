<script type="text/javascript">
    var companies = <?php echo $companies; ?>;
    var stations = <?php echo $stations; ?>;
</script>

<header>
        <?php
            if($this->params['admin']) {
                echo $this->element('admin_badge');
            }
        ?>
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
                echo $this->Form->input('RequestDetail.date', array(
                    'type'  => 'text',
                    'autocomplete' => 'off',
                    'label' => array('text' => '日付'),
                    'id'    => 'datepicker',
                ));
                echo $this->Form->input('RequestDetail.client', array(
                    'label'       => array('text' => 'クライアント名'),
                    'placeholder' => '訪問先が無い場合は空欄',
                    'class'       => 'form-control',
                    'id'          => 'company_autocomplete',
                ));
                echo $this->Form->button("ボタン", array(
                    'type' => 'button',
                    'class' => 'btn btn-secondary',
                    'id' => 'search_travel_section',
                ));
                echo $this->Form->input('RequestDetail.transportation_id', array(
                    'options' => $transportation_id_list,
                    'label'   => '交通手段',
                ));
                echo $this->Form->input('RequestDetail.is_season_ticket', array(
                    'type' => 'checkbox',
                    'label' => '定期',
                ));
                echo $this->Form->input('RequestDetail.from_station',  array(
                    'label'       => array('text' => '利用区間'),
                    'placeholder' => '乗車駅',
                    'class'       => 'form-control',
                    'id'          => 'from_station_autocomplete',
                ));
                echo $this->Form->input('RequestDetail.to_station', array(
                    'label'       => array('text' => ''),
                    'placeholder' => '降車駅',
                    'class'       => 'form-control',
                    'id'    => 'to_station_autocomplete',
                ));
                echo $this->Form->input('RequestDetail.cost', array(
                    'label' => array('text' => '費用'),
                    'class' => 'form-control',
                    'id' => 'cost',
                ));
            ?>
            <div class="small caution">定期を考慮した金額を、片道の料金で入力してください</div>
            <?php
                echo $this->Form->input('RequestDetail.oneway_or_round', array(
                     'type' => 'select',
                     'options' => $oneway_or_round,
                     'label' => '往復or片道',
                     'selected' => '片道',
                 ));
                 echo $this->Form->input('RequestDetail.overview', array(
                    'label' => array('text' => '備考'),
                    'class' => 'form-control',
                ));
            ?>
            <div id="test"></div>
            <div class="text-right">
            <?php
                echo $this->Form->button(__('続けて登録'), array(
                    'class' => 'btn btn-myset',
                    'name' => 'add_repeat')
                );
                echo $this->Form->button(__('登録'), ['class' => 'btn btn-myset', 'name' => 'add']);
                echo $this->Form->end();
            ?>
             <button type="button" onclick="history.back()" class="btn page-link text-dark d-inline-block">Cancel</button>
            </div>
        </div>
    </div>
</div>

<footer class="footer"></footer>
<?php echo $this->Html->script('search_section'); ?>