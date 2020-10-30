<?php
use app\widgets\CalendarWidget;
use yii\helpers\Html;
use yii\helpers\Json;
?><script>
    var skips_list = <?=Json::encode($skips_mapping)?>;
    function rewindCallback(e) {
        var mode = e.target.getAttribute('x-action');
        var target = new Date(), selected;
        target.setDate(1);
        if((selected = (/date=\d+-\d+/).exec(location.search)) !== null) {
            selected = selected[0].split('=')[1].split('-');
            target.setFullYear(selected[0]);
            target.setMonth(selected[1] - 1);
            console.log(selected, target);
        }
        if(mode == 'date-forward') {
            target.setMonth(target.getMonth() + 1);
        } if(mode == 'date-backward') {
            target.setMonth(target.getMonth() - 1);
        }
        console.log('->', target);
        var date_ = target.getFullYear() + '-' + (target.getMonth() + 1);
        var search = location.search.replaceAll(/(&|\?)date=\d+-\d+/g, '');
        search += (search == '' ? '?' : '&') + 'date=' + date_;
        location.search = search;
    }
    function modalCallback(e) {
        var date_ = e.target.getAttribute('x-date');
        var skips = skips_list[date_] || [];
        $('#modal-date').text(date_);
        $('#modal-table-list').html('');
        if(skips.length) {
            for(var i = 0; i < skips.length; i++) {
                var row = $('<tr></tr>'), data = skips[i];
                $('<td></td>').text(data.number).appendTo(row);
                $('<td></td>').text(data.discipline).appendTo(row);
                $('<td></td>').text(data.teacher).appendTo(row);
                $('<td></td>').text(data.hours).appendTo(row);
                row.appendTo($('#modal-table-list'));
            }
        } else {
            var elem = $('<tr><td colspan="4">Нет пропусков</td></tr>');
            elem.appendTo($('#modal-table-list'));
        }
        $('#modal-skips').modal();
    }
</script>
<div class="modal fade" id="modal-skips">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Пропуски за <span id="modal-date">undefined</span></h4>
            </div>
            <div class="modal-body">
                <table class="table">
                    <thead>
                        <tr>
                            <th>№ пары</th>
                            <th>Дисциплина</th>
                            <th>Преподаватель</th>
                            <th>Часов</th>
                        </tr>
                    </thead>
                    <tbody id="modal-table-list">
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<div class="admin-default-index">
    <h1>Пропуски занятий</h1>
    <h2>За <?=$display_date?></h2>
    <p>Пропусков за этот месяц: <?=$hours_monthly?></p>
    <?php
    $calend = CalendarWidget::begin(compact('month', 'year'));
    foreach($skips_merged as $day => $hours) {
        $calend->addDay($day, sprintf('Часов пропущено: %s', $hours));
    }
    $calend->setDateChangeCallback('rewindCallback(event);');
    $calend->setDateSelectCallback('modalCallback(event);');
    echo $calend->run();
    ?>
    <p><i>Цвета: <span class="text-success">Сегодня</span>, <span class="text-danger">выходной</span>, <span class="text-info">пропуск</span></i></p>
    <p><i>Для подробного списка за день, нажмите на него</i></p>
</div>
