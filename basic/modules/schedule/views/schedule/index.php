<?php

    use yii\helpers\{Html, Url};
    use yii\grid\GridView;

    /* @var $this yii\web\View */
    /* @var $searchModel app\models\SheduleSearch */
    /* @var $dataProvider yii\data\ActiveDataProvider */

    $this->title = 'Shedules';
?>

<style>

    .lesson {
        width: 400px;
        height: 145px;
        border: 1px solid;
        display: grid;
        grid-template-columns: 20px 1fr 100px;
        grid-template-rows: 25px 60px 60px;
        grid-template-areas: "num lp lp"
                             "num sb1 sb1p"
                             "num sb2 sb2p";
    }
    .lesson-number { grid-area: num; }
    .lesson-props { grid-area: lp; }
    .subgroup1 { grid-area: sb1; }
    .subgroup1props { grid-area: sb1p; }
    .subgroup2 { grid-area: sb2; }
    .subgroup2props { grid-area: sb2p; }

    /* .lesson > div {
        border: 1px solid;
    } */
    input, select {
        /* border-radius: 0;
        border: 1px solid black; */
        margin: 0;
        padding: 0;
        background: white;
        color: black;
    }
    input:disabled,
    select:disabled {
        background: lightgray;
        color: gray;
    }
    label.disabled {
        color: gray;
    }
    select.main,
    select.repl {
        width: calc(100% - 25.5px) !important;
        height: 24px !important;
        border-right: 0px !important;
    }
    select.main { border-top: 0px; }
    select.repl { border-bottom: 0px; }
    .tch-sel span {
        display: inline-block;
        width: 20px;
    }
    .subgroup1props,
    .subgroup2props {
        display: grid;
        grid-template-columns: 60px 60px;
        grid-template-rows: 25px 25px;
    }
    .tch-sel {
        width: 100% !important;
    }
    .subgroup1props label,
    .subgroup2props label {
        display: block;
    }
    #status {
        display: inline-block;
    }
    .sch-head {
        background: rgba(100, 0, 0, 0.2);
    }

</style>

<div id="myApp">
    <label for="dateSelect">Дата</label>
    <input type="date" name="" id="dateSelect">
    <input type="button" value="Сохранить" id="saveButton">
    <input type="button" value="Excel" id="exportButton">
    <input type="button" value="Неделю назад" id="get-week1">
    <input type="button" value="2 недели назад" id="get-week2">
    <div id="status"></div>
    <br>
    <div id="scheduleGrid">
    </div>
</div>
<script>

var schedule = new (function Site() {
    this.lessonsPerDay = 6;

    this.L_DEFAULT = 0;
    this.L_ERROR = 1;
    this.L_SUCCESS = 2;

    this.TLS_CACHE_KEY = 'tls-cache';

    this.tlsinfo = {}
    this.elemcache = {}

    this.status_colors = {
        "0": '#FFFF9C',
        "1": '#FF9C9C',
        "2": '#9CFF9C',
    }

    this.setStatus = function(text, level) {
        level = level || 0;
        $("#status").text(text);
        $("#status").fadeIn(10)
            .css("background-color", this.status_colors[level])
            .delay(2000).fadeOut(1500);
    }


    this.getCachedTLSInfo = function() {
        var stored = localStorage.getItem(this.TLS_CACHE_KEY);
        if(!stored) return null;
        try {
            return JSON.parse(stored);
        } catch(e) {
            return null;
        }
    }

    this.setCachedTLSInfo = function(tlsinfo) {
        localStorage.setItem(this.TLS_CACHE_KEY, JSON.stringify(tlsinfo));
    }

    this.getActualTLSInfo = function() {
        this.setStatus('Получаем нагрузки...', 0);
        var xhr = new XMLHttpRequest();
        xhr.open('GET', '<?= Url::to(["/schedule/schedule/gettls"]) ?>', false);
        xhr.send()
        var d = xhr.responseText;
        this.setStatus('Нагрузки получены', 2);
        return 'string' == typeof d ? JSON.parse(d) : d;
    }

    this.getTLSInfo = function(actualVersion) {
        var cachedTls = this.getCachedTLSInfo();
        if(!cachedTls || (actualVersion != cachedTls.versionTLS && actualVersion != null) || true) {
            var actualInfo = this.getActualTLSInfo();
            this.setCachedTLSInfo(actualInfo);
            return actualInfo;
        }
        return cachedTls;
    }

    this.optimizeTeacherLoads = function() {
        var optimized = {}
        var info = this.tlsinfo.courses;
        for (var i = 0; i < info.length; i++) {
            var course = info[i];
            for (var j = 0; j < course.groups.length; j++) {
                var group = course.groups[j];
                optimized[group.id] = group.teacherLoads;
            }
        }
        this.tlsinfo.optimized = optimized;
    }

    this.setLockedState = function(state) {
        $('#scheduleGrid').css({
            'filter': state ? 'grayscale(0.5)' : '',
            'opacity': state ? '0.5' : '1.0'
        });
        $("#dateSelect").prop('disabled', state);
    }

    this.getSchedule = function(weeksAgo, changeSelected) {
        var date = $('#dateSelect').val();
        if(date === '') {
            alert('Сначала нужно ввести дату');
            this.setStatus("Дата не введена", 1);
            return;
        }
        if(weeksAgo == 0) {
            this.getScheduleForDate(date);
        } else {
            var oDate = new Date(date);
            var delta = (7 * 24 * 3600 * weeksAgo * 1000);
            oDate.setTime(oDate.getTime() - delta);
            var reqDate = oDate.toISOString().split("T")[0];
            this.getScheduleForDate(reqDate);
            if(!changeSelected) {
                $('#dateSelect').val(date);
            }
        }
    }

    this.getScheduleForDate = function(date) {
        var self = this;
        this.setStatus("Получаем расписание за " + date, 0);
        this.setLockedState(true);
        $.ajax({
            'type': 'POST',
            'url': '<?= Url::to(["/schedule/schedule/getdata"]) ?>',
            'data': {
                'needDate': date
            },
            'dataType': 'json',
            'error': function(xhr) {
                console.error('Failed:', xhr);
                self.setStatus("Ошибка при получении расписания", 1);
            },
            'success': function(d) {
                console.log(d);
                var data = 'string' == typeof d ? JSON.parse(d) : d;
                self.tlsinfo = self.getTLSInfo();
                self.elemcache.teachersel = self.makeTeacherSelector();
                self.optimizeTeacherLoads();
                self.setStatus("Расписание получено", 2);
                $("#scheduleGrid").html("");
                for (var i = 0; i < data.courses.length; i++) {
                    var courseElem = self.processCourse(data.courses[i]);
                    courseElem.appendTo($('#scheduleGrid'));
                }
                self.setLockedState(false);
            }
        });
    }

    this.processCourse = function(course) {
        var courseElem = $('<div class="course"></div>');
        var header = $('<h3></h3>').attr('data-number', course.number);
        header.text(course.number + ' курс');
        header.appendTo(courseElem);
        var table = $('<table></table>');
        for (var i = 0; i < course.groups.length; i += 2) {
            var head_row = $('<tr class="sch-head"></tr>');
            var sch_row = $('<tr class="sch-row"></tr>');
            var grp1 = course.groups[i];
            var grp2 = course.groups[i + 1];
            var cspan =  grp2 == undefined ? 2 : 1;
            head_row.append($('<td></td>').append($('<h4></h4>').text(grp1.name)).attr('colspan', cspan));
            sch_row.append(this.processGroup(grp1).attr('colspan', cspan));

            if(grp2 !== undefined) {
                head_row.append($('<td></td>').append($('<h4></h4>').text(grp2.name)));
                sch_row.append(this.processGroup(grp2));
            }
            head_row.appendTo(table);
            sch_row.appendTo(table);
        }
        table.appendTo(courseElem);
        return courseElem;
    }

    this.processGroup = function(group) {
        var elem = $('<td class="group"></td>').attr('data-id', group.id);
        for(var i = 1; i <= this.lessonsPerDay; i++) {
            var lElem = this.processLesson(
                i,
                this.findLessonsByNumber(i, group.shedule),
                this.tlsinfo.optimized[group.id]
            );
            lElem.appendTo(elem);
        }
        return elem;
    }

    this.findLessonsByNumber = function(ndx, lessons) {
        var result = {'type': 0, 'lessons': [{}, {}]};
        for (var i = 0; i < lessons.length; i++) {
            if(parseInt(lessons[i].number) == ndx) {
                switch(lessons[i].type) {
                    case '':
                        result['type'] = 4;
                        result['lessons'] = [lessons[i], {}];
                        break;
                    case 'I':
                        result['type'] |= 1;
                        result['lessons'][0] = lessons[i];
                        break;
                    case 'II':
                        result['type'] |= 2;
                        result['lessons'][1] = lessons[i];
                        break;
                }
            }
        }
        return result;
    }

    this.makeTeacherLoadsSelector = function(loads, selected) {
        var label = $('<label class="tch-sel"></label>');
        label.attr('title', 'Кто должен преподавать');
        $('<span>П:</span>').appendTo(label);
        var select = $('<select class="main"></select>');
        for(var tKey in loads) {
            var option = $('<option></option>');
            option.attr('value', loads[tKey].id);
            option.text(loads[tKey].text);
            select.append(option);
        }
        select.val(selected);
        select.appendTo(label);
        select.on('input', function() {
            $(this).blur();
        });
        return label;
    }

    this.makeTeacherSelector = function() {
        var label = $('<label class="tch-sel"></label>');
        label.attr('title', 'Кто заменяет');
        $('<span>З:</span>').appendTo(label);
        var select = $('<select class="repl"></select>');
        for(var i = 0; i < this.tlsinfo.teachers.length; i++) {
            var teacher = this.tlsinfo.teachers[i];
            var option = $('<option></option>');
            option.attr('value', teacher.id);
            option.text([teacher.lName, teacher.fName, teacher.mName].join(" "));
            select.append(option);
        }
        select.appendTo(label);
        return label;
    }

    this.makeCachedTeacherSelector = function(selected) {
        var node = this.elemcache.teachersel.clone();
        var selnode = node.find('select');
        selnode.val(selected);
        if(selected == null) {
            selnode.prop('disabled', true);
        }
        selnode.on('input', function() {
            $(this).blur();
        });
        return node;
    }

    this.createCheckbox = function(title, disp, value) {
        value = value || false;
        var label = $('<label></label>');
        label.attr('title', title);
        $('<input>').attr('type', 'checkbox').prop('checked', value).appendTo(label);
        $('<span></span>').text(disp).appendTo(label);
        return label;
    }

    this.createPropsSelectors = function(elem, props) {
        props = props || {};
        props.cons = parseInt(props.cons || 0) == 1;
        props.forTeach = parseInt(props.forTeach || 0) == 1;
        props.kp = parseInt(props.kp || 0) == 1;
        props.replace = parseInt(props.replaceTeacherId || 0) != 0;
        this.createCheckbox('Для учебной части', 'УЧ', props.forTeach).appendTo(elem);
        this.createCheckbox('Курсовое проектирование', 'КП', props.kp).appendTo(elem);
        this.createCheckbox('Консультация', 'К', props.cons).appendTo(elem);
        this.createCheckbox('Замена преподавателя', 'ЗМ', props.replace).appendTo(elem);
    }

    this.setSecondSubgroupState = function(elem, state) {
        state = Boolean(state);
        elem.find('.subgroup2 select, .subgroup2props input').each(function(i, v) {
            $(v).prop('disabled', !!!state);
        });
        if(!elem.find('.subgroup2props :nth-child(4) input').prop("checked")) {
            elem.find('.subgroup2 select.repl').prop('disabled', true);
        }
        elem.find('.subgroup2props label').each(function(i, v) {
            if(!!!state) {
                $(v).addClass('disabled');
            } else {
                $(v).removeClass('disabled');
            }
        });
    }

    this.processLesson = function(num, lessons, loads) {
        var self = this;
        var elem = $('<div class="lesson"></div>');
        var lprops = $('<div class="lesson-props"></div>');

        var comb_lb = $('<label></label>').attr('title', 'Общая пара');
        $('<input>').attr('type', 'checkbox').prop('checked', lessons.type == 4 || lessons.type == 0).appendTo(comb_lb);
        $('<span></span>').text('общ.').appendTo(comb_lb);
        comb_lb.find('input').on('change', function(e) {
            self.setSecondSubgroupState(elem, !this.checked);
        });
        comb_lb.appendTo(lprops);

        var subgroup1 = $('<div class="subgroup1"></div>');
        var lessonsg1 = lessons.lessons.length ? lessons.lessons[0] : {};
        subgroup1.append(this.makeTeacherLoadsSelector(loads, lessonsg1.teacherLoadId));
        subgroup1.append(this.makeCachedTeacherSelector(lessonsg1.replaceTeacherId));

        var subgroup1props = $('<div class="subgroup1props"></div>');
        this.createPropsSelectors(subgroup1props, lessonsg1);
        subgroup1props.find(":nth-child(4)").find('input').on('change', function(e) {
            subgroup1.find('label:nth-child(2) select').prop('disabled', !this.checked);
        });

        var subgroup2 = $('<div class="subgroup2"></div>');
        var lessonsg2 = lessons.lessons.length > 1 ? lessons.lessons[1] : {};
        subgroup2.append(this.makeTeacherLoadsSelector(loads, lessonsg2.teacherLoadId));
        subgroup2.append(this.makeCachedTeacherSelector(lessonsg2.replaceTeacherId));

        var subgroup2props = $('<div class="subgroup2props"></div>');
        this.createPropsSelectors(subgroup2props, lessonsg2);
        subgroup2props.find(":nth-child(4)").find('input').on('change', function(e) {
            subgroup2.find('label:nth-child(2) select').prop('disabled', !this.checked);
        });

        $('<div class="lesson-number"></div>').text(num).appendTo(elem);
        lprops.appendTo(elem);
        subgroup1.appendTo(elem);
        subgroup1props.appendTo(elem);
        subgroup2.appendTo(elem);
        subgroup2props.appendTo(elem);
        this.setSecondSubgroupState(elem, lessons.type > 0 && lessons.type < 4);
        return elem;
    }

    this.saveData = function() {
        this.setStatus("Сохраняем...", 0);
        this.setLockedState(true);
        var result = {
            "items": [],
            "date": $("#dateSelect").val()
        };
        $(".course").each(function(i, cElem) {
            $(cElem).find(".group").each(function(i, gElem) {
                $(gElem).find('.lesson').each(function(i, lElem) {
                    var lJElem = $(lElem);
                    var isMerged = lJElem.find('.lesson-props input')[0].checked;
                    var number = parseInt(lJElem.find(".lesson-number").text());
                    var lesson = {
                        "number": number,
                        "type": isMerged ? "" : "I",
                        "teacherLoadId": lJElem.find(".subgroup1 .main").val(),
                        "forTeach": lJElem.find(".subgroup1props :nth-child(1) input")[0].checked * 1,
                        "kp": lJElem.find(".subgroup1props :nth-child(2) input")[0].checked * 1,
                        "cons": lJElem.find(".subgroup1props :nth-child(3) input")[0].checked * 1,
                        "replaceTeacherId": (
                            lJElem.find(".subgroup1props :nth-child(4) input")[0].checked
                            ? lJElem.find(".subgroup1 .repl").val()
                            : null
                        ),
                        "date": result.date,
                        // "sr": ""
                    };
                    if(lesson.teacherLoadId > 0)
                        result.items.push(lesson);
                    if(!isMerged) {
                        lesson = {
                            "number": number,
                            "type": "II",
                            "teacherLoadId": lJElem.find(".subgroup2 .main").val(),
                            "forTeach": lJElem.find(".subgroup2props :nth-child(1) input")[0].checked ? "1" : "0",
                            "kp": lJElem.find(".subgroup2props :nth-child(2) input")[0].checked ? "1" : "0",
                            "cons": lJElem.find(".subgroup2props :nth-child(3) input")[0].checked ? "1" : "0",
                            "replaceTeacherId": (
                                lJElem.find(".subgroup2props :nth-child(4) input")[0].checked
                                ? lJElem.find(".subgroup2 .repl").val()
                                : null
                            ),
                            "date": result.date,
                            // "sr": ""
                        };
                        if(lesson.teacherLoadId > 0)
                            result.items.push(lesson);
                    }
                });
            });
        });
        console.log(JSON.stringify(result));
        var self = this;
        $.ajax({
            'type': "POST",
            'url': "<?= Url::to(['/schedule/schedule/save']); ?>",
            'data': result,
            'dataType':'text',
            'error': function(xhr) {
                console.error('Failed:', xhr.responseText);
                self.setStatus("Ошибка при сохранении", 1);
                self.setLockedState(false);
            },
            'success': function (response) {
                console.log('OK', response);
                self.setStatus("Расписание сохранено", 2);
                self.setLockedState(false);
            }
        });
    }

    // TODO
    this.exportTable = function() {
        var url = "<?= Url::to(['/shedule/get-file', 'date' => '']); ?>" + $("#dateSelect").val();
        window.open(url);
    }
})();

window.addEventListener('load', function() {
    setTimeout(function() {
        $('#dateSelect').val(new Date().toISOString().split("T")[0]);
        schedule.getSchedule(0);
    }, 1000);
    $('#dateSelect').on('input', function() { schedule.getSchedule(0, false); });
    $('#saveButton').on('click', function() { schedule.saveData(); });
    $('#exportButton').on('click', function() { schedule.exportTable(); });
    $('#get-week1').on('click', function() { schedule.getSchedule(1, false); });
    $('#get-week2').on('click', function() { schedule.getSchedule(2, false); });
    if(!localStorage.getItem('DEV_ENV')) {
        window.addEventListener('beforeunload', function(e) {
            e.returnValue = "";
            if(!confirm("Действительно хотите выйти?")) {
                e.preventDefault();
            }
            return "Действительно хотите выйти?";
        });
    }
});

</script>
