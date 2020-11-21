<?php ?>
<h3>Tracker</h3>
<p>Current Task</p>
<div id="current-task" class="row current-task">
    <input type="text" name="name" id="current-name" placeholder="Current Task">
    <button id="current-play" class="play" onclick="currentPlay()">Play</button>
    <button id="current-stop" class="stop" onclick="currentStop()">Stop</button>
    <div>
        <label for="current-start">Time start</label>
        <input type="time" name="current-start" id="current-start" step="1">
    </div>
    <div>
        <label for="current-finish">Time finish</label>
        <input type="time" name="current-finish" id="current-finish" step="1">
    </div>
    <div>
        <label for="current-duration">Duration</label>
        <input type="time" name="current-duration" id="current-duration" step="1">
    </div>
    <button id="current-remove" class="remove" onclick="alert('Remove issue')">Remove</button>
</div>

<style>
    .row {
        display: flex;
    }
</style>

<script>
    const tracker = {
        countSwitch: false,
        current: {
            id: null,
            name: "",
            start: null,
            finish: null,
            duration: null,
            state: null
        }
    };
</script>

<!-- define all global variables-->
<!-- 1. Установить начальное значение глобальной переменной countSwitch false-->
<!-- 1. Когда загрузиться страница, записать вызов функции timeTick каждую секунду бесконечно-->

<!--// 1. При нажатии кнопки play сделать disabled play button, взять текущее время с jS и сохранить его в глобальную переменную времени начала таска currentTimeStart-->
<!--// 1. Обратиться к базе данных (ajax) с целью создания нового таска (передаю: название таска name, время начала time_start, state_active true; получаем: id, response_code 1-OK, response_message)-->
<!--// Если response_code не 1-OK, то вывести response_message и прервать текущее действие-->
<!--// Сохранить id в глобальную переменную currentId-->
<!---->
<!--// Запустить таймер: вывести время начала в инпут с id="time-start", установить глобальную переменную countSwitch, которая определяет пересчитывает таймер или нет-->
<!--//  function timeTick() если в переменной countSwitch установлено значение true, то считаем как разницу между текущим временем(JS) и временем начала таска currentTimeStart-->
<!--// вывод в id="duration",-->

<script>
    function timeTick() {
        // то считаем как разницу между текущим временем(JS) и временем начала таска currentTimeStart
        // вывод в id="duration",
        if (tracker.countSwitch) {
            let timeNow = new Date();
            let diff = timeNow - tracker.current.start;
            document.getElementById('current-duration').value = new Date(diff).toISOString().substr(11, 8);
            tracker.current.duration = diff;
        }
    }

    document.addEventListener('DOMContentLoaded', function () {
        setInterval(timeTick, 1000);
    });

    function currentPlay() {
        document.getElementById('current-play').disabled = true;

        let currentDate = new Date();
        tracker.current.start = currentDate;

        let year = currentDate.getFullYear();
        let month = ('0' + (currentDate.getMonth() + 1)).substr(-2,2);
        let day = ('0' + currentDate.getDay()).substr(-2,2);
        let hours = ('0' + currentDate.getHours()).substr(-2,2);
        let min = ('0' + currentDate.getMinutes()).substr(-2,2);
        let sec = ('0' + currentDate.getSeconds()).substr(-2,2);
        let time = hours + ":" + min + ":" + sec;
        let fullTime = year + "-" + month + "-" + day + " " + time;

        document.getElementById('current-start').value = time;

        //название таска name, время начала time_start, state_active true
        let name = document.getElementById("current-name").value;
        tracker.current.name = name;

        $.ajax({
            url: '/tracker/create',
            type: 'post',
            data: {
                'name': name,
                'time_start': fullTime, // in timestamp format
                'state_active': 1 // true
            },
            dataType : "json",
        })
        .success(function (resultData) {
            if (resultData.status.code !== 1) {
                alert(resultData.status.message);
                return;
            }
            tracker.current.id = resultData.data.id;
            tracker.countSwitch = true;
            tracker.current.state = 1;
        });
    }

    // todo
    function currentStop() {
        document.getElementById('current-play').disabled = false;
        tracker.countSwitch = false;
        tracker.current.state = 0;
    }

</script>