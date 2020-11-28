<?php ?>
<div id="current-task" class="row current-task"></div>
<div id="completed-tasks"></div>

<template id="template-current-task">
    <input type="text" name="name" id="current-name" placeholder="Current Task">
    <button id="current-play" class="play" onclick="timerStart()">Play</button>
    <button id="current-stop" class="stop" onclick="timerStop()" disabled>Stop</button>
    <div>
        <label for="current-start">Time start</label>
        <input type="time" name="current-start" id="current-start" step="1" disabled>
    </div>
    <div>
        <label for="current-duration">Duration</label>
        <input type="time" name="current-duration" id="current-duration" step="1" disabled>
    </div>
    <button id="current-remove" class="remove" onclick="timerDelete()">Remove</button>
</template>

<template id="template-tasks-day-container">
    <div class="tasks-day" id="task-day-^^^date^^^"></div>
</template>

<template id="template-task-header">
    <div class="task-header">
        <div>^^^date-string^^^</div>
        <div>
            <input type="time" step="1" value="^^^duration-day^^^" disabled>
        </div>
    </div>
</template>

<template id="template-task-sum">
    <div>^^^name^^^</div>
    <button id="current-play" class="play" onclick="timerStart('^^^id^^^')">Play</button>
    <div>
        <input type="time" step="1" value="^^^time-start^^^" disabled> - <input type="time" step="1" value="^^^time-finish^^^" disabled>
    </div>
    <div>
        <input type="time" step="1" value="^^^duration-sum^^^" disabled>
    </div>
    <button class="row-btn-collapsible">Expand</button>
</template>

<template id="template-task">
    <div>^^^name^^^</div>
    <button id="current-play" class="play" onclick="timerStart('^^^id^^^')">Play</button>
    <div>
        <input type="time" step="1" value="^^^time-start^^^" disabled> - <input type="time" step="1" value="^^^time-finish^^^" disabled>
    </div>
    <div>
        <input type="time" step="1" value="^^^duration^^^" disabled>
    </div>
    <button class="remove" onclick="timerDelete('^^^id^^^')">Remove</button>
</template>


<style>
    .tab-tracker .row {
        display: flex;
    }
</style>

<script>
    const tracker = {
        default: {
            id: null,
            name: "",
            start: null,
            finish: null,
            duration: 0,
            state: 0,
            date: null
        },
        current: {
            id: null,
            name: "",
            start: null,
            finish: null,
            duration: 0,
            state: 0,
            date: null
        },
        tasks: {
            <?php //echo $this->getAllTasksDataInJsFormat() ?>
        }
    }
</script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        resetCurrentTaskRow();
        setInterval(timeTick, 1000);
    });

    /** Get object with year, month, ... min, sec by Date() */
    function getTimeParts(dateTime) {
        let timeObj = {};
        timeObj.year = dateTime.getFullYear();
        timeObj.month = ('0' + (dateTime.getMonth() + 1)).substr(-2, 2);
        timeObj.day = ('0' + dateTime.getDate()).substr(-2, 2);
        timeObj.hours = ('0' + dateTime.getHours()).substr(-2, 2);
        timeObj.min = ('0' + dateTime.getMinutes()).substr(-2, 2);
        timeObj.sec = ('0' + dateTime.getSeconds()).substr(-2, 2);
        timeObj.time = timeObj.hours + ":" + timeObj.min + ":" + timeObj.sec;
        timeObj.date = timeObj.year + "-" + timeObj.month + "-" + timeObj.day;
        timeObj.fullTime = timeObj.date + " " + timeObj.time;
        return timeObj;
    }

    /** Update current duration time */
    function timeTick() {
        if (tracker.current.state) {
            let timeNow = new Date();
            let diff = timeNow - tracker.current.start;
            document.getElementById('current-duration').value = new Date(diff).toISOString().substr(11, 8);
            tracker.current.duration = diff;
        }
    }

    function timerStart(taskId = null) {
        timerStop();
        if (taskId !== null) {
            document.getElementById("current-name").value = tracker.tasks[taskId].name;
        }
        document.getElementById('current-play').disabled = true;
        document.getElementById('current-stop').disabled = false;
        let currentDate = new Date();
        tracker.current.start = currentDate;
        let timeObj = getTimeParts(currentDate);
        document.getElementById('current-start').value = timeObj.time;
        let name = document.getElementById("current-name").value;
        tracker.current.name = name;
        tracker.current.date = timeObj.date;
        tracker.current.state = 1;
        $.ajax({
            url: '/tracker/create',
            type: 'post',
            data: {
                'name': name,
                'time_start': timeObj.fullTime, // in timestamp format
                'state_active': 1 // true
            },
            dataType: "json",
        })
            .success(function (resultData) {
                if (resultData.status.code !== 1) {
                    alert(resultData.status.message);
                    return;
                }
                tracker.current.id = resultData.data.id;
            });
    }

    function timerStop() {
        if (tracker.current.state === 0) {
            return;
        }
        document.getElementById('current-stop').disabled = true;
        document.getElementById('current-play').disabled = false;
        let currentDate = new Date();
        let timeObj = getTimeParts(currentDate);
        tracker.current.state = 0;
        tracker.current.finish = currentDate;
        tracker.current.name = document.getElementById("current-name").value;
        tracker.tasks[tracker.current.id] = tracker.current;

        $.ajax({
            url: '/tracker/stop',
            type: 'post',
            data: {
                'id': tracker.current.id,
                'name': tracker.current.name,
                'time_finish': timeObj.fullTime, // in timestamp format
                'state_active': 0, // false
                'duration': parseInt(tracker.current.duration / 1000),
            },
            dataType: "json",
        })
            .success(function (resultData) {
                if (resultData.status.code !== 1) {
                    alert(resultData.status.message);
                }
            });

        resetCurrentTaskRow();
        timerUpdateSection(tracker.current.date);
        tracker.current = Object.assign({}, tracker.default);
    }

    function timerDelete(taskId = null) {
        if (taskId === null) { // removing current task
            if (tracker.current.state) {
                $.ajax({
                    url: '/tracker/delete',
                    type: 'post',
                    data: {
                        'id': tracker.current.id
                    },
                    dataType: "json",
                })
                    .success(function (resultData) {
                        if (resultData.status.code !== 1) {
                            alert(resultData.status.message);
                        }
                    });
                tracker.current = Object.assign({}, tracker.default);
            }
            resetCurrentTaskRow();
        } else { // removing of non-current task
            $.ajax({
                url: '/tracker/delete',
                type: 'post',
                data: {
                    'id': tracker.current.id
                },
                dataType: "json",
            })
                .success(function (resultData) {
                    if (resultData.status.code !== 1) {
                        alert(resultData.status.message);
                    }
                });

            let date = tracker.tasks[taskId].date;
            delete tracker.tasks[taskId];
            timerUpdateSection(date);
        }
    }

    function resetCurrentTaskRow() {
        document.getElementById('current-task').innerHTML = document.getElementById('template-current-task').innerHTML;
    }

    function timerUpdateSection(date) { // date = "2020-28-11"
        let dayContainer = document.getElementById('task-day-' + date);

        //add container for single day tasks
        if (dayContainer === null) {
            var dayContainerHtml = document.getElementById('template-tasks-day-container').innerHTML;
            dayContainerHtml = dayContainerHtml.replace('^^^date^^^', date);
            let whereToAdd = document.getElementById('completed-tasks');
            let whereToAddHtml = whereToAdd.innerHTML;
            if (getTimeParts(new Date()).time === date) {
                whereToAdd.innerHTML = dayContainerHtml + whereToAddHtml;
            } else {
                whereToAdd.innerHTML = whereToAddHtml + dayContainerHtml;
            }
            dayContainer = document.getElementById('task-day-' + date);
        }

        // collect all tasks for required day
        let dayTasks = {};
        let dayTasksDuration = 0;
        let singleTasksHtml = '';
        let lastId;
        for (const taskId in tracker.tasks) {
            if (tracker.tasks[taskId].date === date) {
                dayTasks[taskId] = Object.assign({}, tracker.tasks[taskId]);
                dayTasksDuration += tracker.tasks[taskId].duration;
                singleTasksHtml += getSingleTaskHtml(taskId);
                lastId = taskId;
            }
        }

        let dateString = tracker.tasks[lastId].start.toString().substring(0, 10);
        let diffString = new Date(dayTasksDuration).toISOString().substr(11, 8);
        let dayTasksHeaderHtml = getTasksHeaderHtml(dateString, diffString);

        dayContainer.innerHTML = dayTasksHeaderHtml + singleTasksHtml;
    }

    function getSingleTaskHtml(id) {
        let html = document.getElementById('template-task').innerHTML;
        html = html.replace('^^^name^^^', tracker.tasks[id].name);
        let timeStart = getTimeParts(tracker.tasks[id].start);
        html = html.replace('^^^time-start^^^', timeStart.time);
        let timeFinish = getTimeParts(tracker.tasks[id].finish);
        html = html.replace('^^^time-finish^^^', timeFinish.time);
        let diffString = new Date(tracker.tasks[id].duration).toISOString().substr(11, 8);
        html = html.replace('^^^duration^^^', diffString);
        html = html.replace('^^^id^^^', id);
        return html;
    }

    function getTasksHeaderHtml(dateString, dayTasksDuration) {
        let html = document.getElementById('template-task-header').innerHTML;
        html = html.replace('^^^date-string^^^', dateString);
        html = html.replace('^^^duration-day^^^', dayTasksDuration);
        return html;
    }
</script>
