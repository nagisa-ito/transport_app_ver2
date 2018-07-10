<?php echo $this->Html->css('sections'); ?>

<div class="row justify-content-center">
    <div class="col-sm-6 mt-4">
        <div class="p-3" id="pagenate_area">
            <span>区間マスタ一覧</span>
            <span class="float-right"><a class="btn btn-black-green">追加</a></span>
        </div>
        <div class="p-3 mt-3" id="output_area">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>行き先</th>
                        <th colspan="2">区間</th>
                        <th>費用</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($sections as $section) : ?>
                    <tr>
                        <td><?php echo $section['Section']['goal']; ?></td>
                        <td><?php echo $section['Section']['from']; ?></td>
                        <td><?php echo $section['Section']['to']; ?></td>
                        <td><?php echo $section['Section']['cost']; ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
