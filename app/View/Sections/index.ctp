<?php echo $this->Html->css('sections'); ?>

<header>
    <?php
        echo $this->element('admin_header', array(
            'title' => '交通費精算表',
            'is_loggedIn' => 1,
            'is_admin' => isset($this->params['admin']) ? 1 : 0,
        ));
    ?>
</header>

<div class="text-center">
    <?php echo $this->Session->flash(); ?>
</div>

<div class="row justify-content-center">
    <div class="col-sm-8 mt-2">
        
        <div id="pagenate_area">
            <span class="ml-2">区間マスタ一覧</span>
            <div class="float-right mr-2">
                <a class="btn btn-black-pink" id="filter_section"><i class="fas fa-search"></i></a>
                <a class="btn btn-black-green" id="add_section"><i class="fas fa-plus"></i></a>
            </div>
        </div>
        
        <div id="add_section_detail">
            <?php
                echo $this->Form->create('Section', array(
                    'class' => 'form-inline'));
                echo $this->Form->input('name', array(
                    'label' => array('text' => '行き先'),
                    'class' => 'form-control form-control-sm',
                    'div' => array('class' => 'form-group mr-2'),
                ));
                echo $this->Form->input('from', array(
                    'label' => array('text' => '区間'),
                    'class' => 'form-control form-control-sm',
                    'div' => array('class' => 'form-group'),
                ));
                echo $this->Form->input('to', array(
                    'label' => false,
                    'class' => 'form-control form-control-sm',
                    'div' => array('class' => 'form-group mr-2'),
                ));
                echo $this->Form->input('cost', array(
                    'label' => array('text' => '費用'),
                    'class' => 'form-control form-control-sm',
                    'div' => array('class' => 'form-group mr-2'),
                ));
                echo $this->Form->button(__('追加'), array('class' => 'btn btn-black-green'));
                echo $this->Form->end();
            ?>
        </div>
        
        <div id="filter_section_detail">
            <?php
                echo $this->Form->create('Section', array(
                    'url' => array('action' => 'index'),
                    'type' => 'GET',
                    'class' => 'form-inline',
                ));
                echo $this->Form->input('search_word', array(
                    'label' => false,
                    'placeholder' => '行き先を検索',
                    'class' => 'form-control form-group-sm',
                    'div' => array('class' => 'form-group mr-2'),
                ));
                echo $this->Form->button(__('検索'), array('class' => 'btn btn-black-pink'));
                echo $this->Form->end();
            ?>
        </div>
        
        <div class="p-3 mt-3" id="output_area">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>行き先</th>
                        <th colspan="2">区間</th>
                        <th>費用</th>
                        <th></th>
                    </tr>
            </thead>
            <tbody>
                <?php foreach($sections as $section) : ?>
                    <tr class="section_detail" id="section_<?php echo h($section['Section']['id']); ?>">
                        <td><?php echo $section['Section']['name']; ?></td>
                        <td><?php echo $section['Section']['from']; ?></td>
                        <td><?php echo $section['Section']['to']; ?></td>
                        <td><?php echo $section['Section']['cost']; ?></td>
                        <td>
                            <?php
                                echo $this->Html->link('<i class="fas fa-times"></i>', '#', array(
                                    'class' => 'delete_icon',
                                    'data-id' => $section['Section']['id'],
                                    'escape' => false,
                                ));
                            ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
            </table>
        </div>

        <div class="text-center mt-3">
            <?php
                echo $this->Paginator->prev('<i class="fas fa-angle-left"></i>',
                                            array('escape' => false),
                                            '<i class="fas fa-angle-left"></i>',
                                            array('class' => 'prev disabled', 'escape' => false)
                );
                echo $this->Paginator->numbers(array('separator' => '', 'class' => 'numbers'));
                echo $this->Paginator->next('<i class="fas fa-angle-right"></i>',
                                            array('escape' => false),
                                            '<i class="fas fa-angle-right"></i>',
                                            array('class' => 'next disabled', 'escape' => false)
                );
            ?>
        </div>
        
    </div>
</div>
<?php echo $this->Html->script('section'); ?>