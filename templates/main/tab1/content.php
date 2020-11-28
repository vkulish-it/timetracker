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
    <button id="current-remove" class="remove" onclick="currentDelete()">Remove</button>
</div>

<template id="test">
    <h2>hello</h2>
</template>


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
        // вывод в id="current-duration"
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

        let timeObj = getTimeParts(currentDate);

        document.getElementById('current-start').value = timeObj.time;

        //название таска name, время начала time_start, state_active true
        let name = document.getElementById("current-name").value;
        tracker.current.name = name;

        $.ajax({
            url: '/tracker/create',
            type: 'post',
            data: {
                'name': name,
                'time_start': timeObj.fullTime, // in timestamp format
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

    function getTimeParts(dateTime) {
        let timeObj = {};
        timeObj.year = dateTime.getFullYear();
        timeObj.month = ('0' + (dateTime.getMonth() + 1)).substr(-2, 2);
        timeObj.day = ('0' + dateTime.getDate()).substr(-2, 2);
        timeObj.hours = ('0' + dateTime.getHours()).substr(-2, 2);
        timeObj.min = ('0' + dateTime.getMinutes()).substr(-2, 2);
        timeObj.sec = ('0' + dateTime.getSeconds()).substr(-2, 2);
        timeObj.time = timeObj.hours + ":" + timeObj.min + ":" + timeObj.sec;
        timeObj.fullTime = timeObj.year + "-" + timeObj.month + "-" + timeObj.day + " " + timeObj.time;
        return timeObj;
    }
    
    // todo
    function currentStop() {
        document.getElementById('current-play').disabled = false;
        tracker.countSwitch = false;
        tracker.current.state = 0;

        let currentDate = new Date();
        tracker.current.finish = currentDate;

        let timeObj = getTimeParts(currentDate);

        document.getElementById('current-finish').value = timeObj.time;

        //название  время окончания time_finish, state_active false, duration

        $.ajax({
            url: '/tracker/stop',
            type: 'post',
            data: {
                'id': tracker.current.id,
                'name': tracker.current.name,
                'time_finish': timeObj.fullTime, // in timestamp format
                'state_active': 0, // false
                'duration': tracker.current.duration / 1000,
            },
            dataType: "json",
        })
            .success(function (resultData) {
                if (resultData.status.code !== 1) {
                    alert(resultData.status.message);
                    return;
                }
                tracker.countSwitch = false;
                tracker.current.state = 0;
            });
    }

        function currentDelete() {

            $.ajax({
                url: '/tracker/delete',
                type: 'post',
                data: {
                    'id': tracker.current.id,
                },
                dataType: "json",
            })
                .success(function (resultData) {
                    if (resultData.status.code !== 1) {
                        alert(resultData.status.message);
                        return;
                    }
                    tracker.countSwitch = false;
                    tracker.current.state = 0;
                });
        }

</script>


<!--// 1. При нажатии кнопки Stop , взять текущее время с jS и сохранить его в глобальную переменную времени окончания таска tracker.current.finish-->
<!--// 1. Обратиться к базе данных (ajax) с целью UPDATE таска (передаю: время конца time_stop, duration, state_active false; получаем?????: response_code 1-OK, response_message)-->
<!--// Если response_code не 1-OK, то вывести response_message и прервать текущее действие-->
<!--// Сохранить time_stop, duration, state_active false в базе -->
<!--// Остановить таймер: вывести время конца в инпут с id="current-finish"-->
