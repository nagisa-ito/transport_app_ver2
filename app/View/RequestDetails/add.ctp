<script type="text/javascript">
    var sections = <?php echo $sections; ?>;
    var stations = <?php echo $stations; ?>;
</script>

<header>
    <?php
        echo $this->element('admin_header', array(
            'title' => '交通費精算表',
            'is_loggedIn' => 1,
            'is_admin' => $this->params['admin'],
        ));
    ?>
</header>

<div class="text-center">
    <?php echo $this->Session->flash(); ?>
</div>

<div class="content row">
    <div class="col-sm-6 offset-sm-3">
        <div class="form_contents mt-4">
            <?php
                echo $this->Form->create();
                echo $this->Form->hidden('user_id', array('default' => $user_id));
                echo $this->Form->input('date', array(
                    'type'  => 'text',
                    'autocomplete' => 'off',
                    'label' => array('text' => '日付'),
                    'id'    => 'datepicker',
                    'class' => 'form-control',
                ));
            ?>
            
            <div class="form-row">
                <?php
                    echo $this->Form->input('trans_type', array(
                        'type' => 'select',
                        'options' => Configure::read('trans_category'),
                        'label' => '分類',
                        'class' => 'form-control',
                        'div' => array(
                            'class' => 'form-group col-4',
                        ),
                    ));
                    echo $this->Form->input('oneway_or_round', array(
                        'type' => 'select',
                        'options' => Configure::read('oneway_or_round'),
                        'label' => '経路',
                        'class' => 'form-control',
                        'div' => array(
                            'class' => 'form-group col-4',
                        ),
                    ));
                    echo $this->Form->input('transportation_id', array(
                        'type' => 'select',
                        'options' => $transportations,
                        'label'   => '交通手段',
                        'class' => 'form-control',
                        'div' => array(
                            'class' => 'form-group col-4',
                        ),
                    ));
                ?>
            </div>

            <div class="mt-2 mb-1">訪問先</div>
            <div class="form-row mb-4">
            <?php
                echo $this->Form->input('client', array(
                    'div' => array(
                        'class' => 'col-10',
                    ),
                    'label'       => false,
                    'placeholder' => '訪問先が無い場合は空欄',
                    'class'       => 'form-control',
                    'id'          => 'company_autocomplete',
                ));
                echo $this->Form->button('<i class="fas fa-search"></i>区間検索', array(
                    'type' => 'button',
                    'class' => 'btn btn-green col-2',
                    'id' => 'search_travel_section',
                ));
            ?>
            </div>

            <p>利用区間</p>
            <div class="form-row">
                <div class="col-sm-1 parent black-round">
                    <p class="small white-str">出発駅</p>
                </div>
                <?php
                    echo $this->Form->input('from_station',  array(
                        'div' => array(
                            'class' => 'col-sm-11 pl-0',
                        ),
                        'label' => false,
                        'class'       => 'form-control',
                        'id'          => 'from_station_autocomplete',
                    ));
                ?>
            </div>

            <div class="col-sm-1"><i class="fas fa-angle-double-down"></i></div>

            <div class="form-row mb-3">
                <div class="col-sm-1 parent black-round">
                    <p class="small white-str">到着駅</p>
                </div>
                <?php
                    echo $this->Form->input('to_station', array(
                        'div' => array(
                            'class' => 'col-sm-11 pl-0',
                        ),
                        'label' => false,
                        'class'       => 'form-control',
                        'id'    => 'to_station_autocomplete',
                    ));
                ?>
            </div>

            <?php
                echo $this->Form->input('cost', array(
                    'label' => array('text' => '費用'),
                    'class' => 'form-control',
                    'id' => 'cost',
                ));
            ?>
            <div class="small note mb-2 text-right">定期区間を含む場合は実際に払った金額を入力</div>

            <?php
                 echo $this->Form->input('overview', array(
                    'label' => array('text' => '備考'),
                    'class' => 'form-control',
                ));
            ?>

            <div class="text-right">
            <?php
                echo $this->Form->button(__('続けて登録'), array(
                    'class' => 'btn btn-purple mr-1',
                    'name' => 'add_repeat')
                );
                echo $this->Form->button(__('登録'), ['class' => 'btn btn-purple mr-1', 'name' => 'add']);
                echo $this->Form->end();
            ?>
             <button type="button" onclick="history.back()" class="btn btn-white">キャンセル</button>
            </div>
        </div>
    </div>
</div>

<?php echo $this->Html->script('search_section'); ?>
