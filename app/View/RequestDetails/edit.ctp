    <header>
        <?php
            if($this->params['admin']) {
                echo $this->element('admin_badge');
            }
        ?>
        <h4 style="display: inline">申請を編集</h4>
    </header>

    <div id="content" class="text-center box24">
        <?php echo $this->Session->flash(); ?>
        <?php echo $this->fetch('content'); ?>
    </div>

    <div class="content row">
        <div class="col-sm-6 offset-sm-3">
            <div class="form_contents">
                <?php
                    echo $this->Form->create('RequestDetail', ['class' => 'form_inline']);
                    echo $this->Form->hidden('RequestDetail.user_id', array('default' => $login_user_id));
                    echo $this->Form->input('RequestDetail.date', array(
                        'type'  => 'text',
                        'label' => array('text' => '日付'),
                        'id'    => 'datepicker',
                    ));
                    echo $this->Form->input('RequestDetail.client', array(
                        'label'       => array('text' => 'クライアント名'),
                        'placeholder' => '訪問先が無い場合は空欄',
                        'class'       => 'form-control',
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
                    ));
                    echo $this->Form->input('RequestDetail.to_station', array(
                        'label'       => array('text' => ''),
                        'placeholder' => '降車駅',
                        'class'       => 'form-control',
                    ));
                    echo $this->Form->input('RequestDetail.cost', array(
                        'label' => array('text' => '費用'),
                        'class' => 'form-control',
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
                <div class="row">
                    <div class="col-sm-7"></div>
                    <div class="col-sm-3">
                    <?php echo $this->Form->button(__('Edit'), ['class' => 'btn btn-myset btn-block']);
                          echo $this->Form->end();
                    ?></div>
                    <div class="col-sm-2">
                        <button type="button" onclick="history.back()" class="btn page-link text-dark d-inline-block">Cancel</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <footer></footer>
