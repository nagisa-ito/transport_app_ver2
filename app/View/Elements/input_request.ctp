<?php
    echo $this->Form->create('RequestDetail');
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

<?php
    echo $this->Form->input('from_station',  array(
        'div'            => array('class' => 'form-row'),
        'class'          => 'form-control col-11',
        'id'             => 'from_station_autocomplete',
        'label'          => array(
            'text'       => '出発駅',
            'class'      => 'col-1 mb-0 small black-label',
        ),
        'error'          => array(
            'attributes' => array(
                'wrap'   => 'div',
                'class'  => 'error-message text-right col-12'
            )
        ),
    ));
?>

<div class="form-row">
    <div class="col-1 text-center"><i class="fas fa-angle-double-down fa-lg"></i></div>
</div>

<?php
    echo $this->Form->input('to_station',  array(
        'div'            => array('class' => 'form-row'),
        'class'          => 'form-control col-11',
        'id'             => 'to_station_autocomplete',
        'label'          => array(
            'text'       => '到着駅',
            'class'      => 'col-1 mb-0 small black-label',
        ),
        'error'          => array(
            'attributes' => array(
                'wrap'   => 'div',
                'class'  => 'error-message text-right col-12'
            )
        ),
    ));
    ?>

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
