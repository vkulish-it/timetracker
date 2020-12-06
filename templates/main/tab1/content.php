<?php ?>
<div id="current-task" class="row current-task"></div>
<div id="completed-tasks"></div>
<div><button id="load-more" onclick="loadMoreTasks()">Load More</button></div>

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
    <div class="task-header row">
        <div>^^^date-string^^^</div>
        <div>
            <input type="time" step="1" value="^^^duration-day^^^" disabled>
        </div>
    </div>
</template>

<template id="template-task-sum">
    <div class="task-group row">
        <div>^^^name^^^</div>
        <button id="current-play" class="play" onclick="timerStart('^^^id^^^')">Play</button>
        <div>
            <input type="time" step="1" value="^^^time-start^^^" disabled> - <input type="time" step="1" value="^^^time-finish^^^" disabled>
        </div>
        <div>
            <input type="time" step="1" value="^^^duration-sum^^^" disabled>
        </div>
        <button class="row-btn-collapsible" onclick="collapseTasks(this)">Collapse</button>
    </div>
</template>

<template id="template-task">
    <div class="single-task row">
        <div>^^^name^^^</div>
        <button id="current-play" class="play" onclick="timerStart('^^^id^^^')">Play</button>
        <div>
            <input type="time" step="1" value="^^^time-start^^^" disabled> - <input type="time" step="1" value="^^^time-finish^^^" disabled>
        </div>
        <div>
            <input type="time" step="1" value="^^^duration^^^" disabled>
        </div>
        <button class="remove" onclick="timerDelete('^^^id^^^')">Remove</button>
    </div>
</template>


<style>
    .tab-tracker .row {
        display: flex;
    }
    .tab-tracker .tasks-day {
        border: 1px solid grey;
    }
    .tab-tracker .task-header {
        background-color: grey;
    }
    .tab-tracker .task-group {
        background-color: lightgrey;
    }
    .tab-tracker .task-group.row .row-btn-collapsible {
        width: auto;
    }
    .tab-tracker .group-tasks.collapsed {
        display: none;
    }

    .tab-tracker .single-task {
        border: 1px solid black;
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
        },
        initial: <?php echo App\Factory::getSingleton(App\Models\Tracker::class)->getJsonUserTasks(); ?>,
    }
</script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        //@todo 2 loadMore
        //@todo
        //@todo 3 = admin pages
        //@todo 4 = statistic
        prepareInitialTasks();
        setInterval(timeTick, 1000);
    });

    function prepareInitialTasks() {
        let tasksSample;
        let updateSectionDates = {};
        let updateCurrent = false;
        Object.keys(tracker.initial).forEach(function(key) {
            let task = Object.assign({},tracker.initial[key]);
            tasksSample = Object.assign({}, tracker.default);
            tasksSample.id = task.id;
            tasksSample.name = task.name;
            tasksSample.start = new Date(task.time_start);
            if (task.time_finish) {
                tasksSample.finish = new Date(task.time_finish);
                tasksSample.duration = tasksSample.finish - tasksSample.start;
            }
            tasksSample.date = getTimeParts(tasksSample.start).date;
            tasksSample.state = task.state_active;
            if (tasksSample.state) {
                tracker.current = tasksSample;
                updateCurrent = true;
            } else {
                updateSectionDates[tasksSample.date] = true;
                tracker.tasks[tasksSample.id] = tasksSample;
            }
        });

        resetCurrentTaskRow();
        if (updateCurrent) {
            document.getElementById("current-name").value = tracker.current.name;
            document.getElementById("current-start").value = getTimeParts(tracker.current.start).time;
            document.getElementById('current-play').disabled = true;
            document.getElementById('current-stop').disabled = false;
        }

        Object.keys(updateSectionDates).forEach(function(date) {
            updateSection(date);
        });
    }

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
        updateSection(tracker.current.date);
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
                    'id': taskId
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
            updateSection(date);
        }
    }

    function resetCurrentTaskRow() {
        document.getElementById('current-task').innerHTML = document.getElementById('template-current-task').innerHTML;
    }

    function updateSection(date) { // date = "2020-28-11"
        let dayContainer = document.getElementById('task-day-' + date);

        //add container for single day tasks
        if (dayContainer === null) {
            var dayContainerHtml = document.getElementById('template-tasks-day-container').innerHTML;
            dayContainerHtml = dayContainerHtml.replaceAll('^^^date^^^', date);
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
        let lastId;
        for (const taskId in tracker.tasks) {
            if (tracker.tasks[taskId].date === date) {
                dayTasks[taskId] = Object.assign({}, tracker.tasks[taskId]);
                dayTasksDuration += tracker.tasks[taskId].duration;
                lastId = taskId;
            }
        }
        if (lastId) {
            let dateString = tracker.tasks[lastId].start.toString().substring(0, 10);
            let diffString = new Date(dayTasksDuration).toISOString().substr(11, 8);
            let dayTasksHeaderHtml = getTasksHeaderHtml(dateString, diffString);

            let allGroupedTasksHtml = "";
            let groupsTasksData = sortToGroupsByName(dayTasks);
            for (let i = 0; i < groupsTasksData.length; i++) {
                allGroupedTasksHtml += getSingleGroupedTasksHtml(groupsTasksData[i])
            }
            dayContainer.innerHTML = dayTasksHeaderHtml + allGroupedTasksHtml;
        } else {
            dayContainer.innerHTML = "";
        }
    }

    function sortToGroupsByName(dayTasks) {
        let groupsData = {};
        let tasks = Object.assign({}, dayTasks);

        // grouping
        // data Example:
        // tasks = {5: {id:5, name: "+"}, 7: {id:7, name: "-"}, 8: {id:8, name: "+"}};
        // ids = [0=>5, 1=>7, 2=>8]; count = 3;

        let ids = Object.keys(tasks);
        for (let j = 0; j < ids.length; j++) {
            let elementName = tasks[ids[j]].name;
            if (elementName in groupsData) {
                continue;
            }
            groupsData[elementName] = {'ids': [ids[j]]};
            for (let i = 1; i < ids.length; i++) {
                let id = ids[i];
                if (tasks[id].name === elementName && !groupsData[elementName].ids.includes(id)) {
                    groupsData[elementName].ids.push(id);
                }
            }
        }

        // add start time, finish time, total duration and lastId to groupsData
        Object.keys(groupsData).forEach( function(name) {
            let group = groupsData[name];
            let taskIds = group.ids;
            let start, finish, lastId;
            let duration = 0;
            for (let i = 0; i < taskIds.length; i++) {
                let taskId = taskIds[i];
                let task = tasks[taskId];
                if (!start || (start && start > task.start)) {
                    start = task.start;
                }
                if (!finish || (finish && finish < task.finish)) {
                    finish = task.finish;
                }
                duration += task.duration;
                lastId = taskId;
            }
            groupsData[name].name = name;
            groupsData[name].start = start;
            groupsData[name].finish = finish;
            groupsData[name].duration = duration;
            groupsData[name].lastId = lastId;
        });

        //sort by time latest is at top, so should be first
        return Object.values(groupsData).sort(function (a, b) {
            return b.finish - a.finish;
        });
    }

    function getSingleGroupedTasksHtml(groupTasksData) {
        let html = document.getElementById('template-task-sum').innerHTML;
        html = html.replaceAll('^^^name^^^', groupTasksData.name);
        let timeStart = getTimeParts(groupTasksData.start);
        html = html.replaceAll('^^^time-start^^^', timeStart.time);
        let timeFinish = getTimeParts(groupTasksData.finish);
        html = html.replaceAll('^^^time-finish^^^', timeFinish.time);
        let diffString = new Date(groupTasksData.duration).toISOString().substr(11, 8);
        html = html.replaceAll('^^^duration-sum^^^', diffString);
        html = html.replaceAll('^^^id^^^', groupTasksData.lastId);

        html += '<div class="group-tasks collapsed">';
        let tasksIds = groupTasksData.ids;
        for (let i = 0; i < tasksIds.length; i++) {
            html += getSingleTaskHtml(tasksIds[i]);
        }
        html += '</div>';

        return html;
    }

    function getSingleTaskHtml(id) {
        let html = document.getElementById('template-task').innerHTML;
        html = html.replaceAll('^^^name^^^', tracker.tasks[id].name);
        let timeStart = getTimeParts(tracker.tasks[id].start);
        html = html.replaceAll('^^^time-start^^^', timeStart.time);
        let timeFinish = getTimeParts(tracker.tasks[id].finish);
        html = html.replaceAll('^^^time-finish^^^', timeFinish.time);
        let diffString = new Date(tracker.tasks[id].duration).toISOString().substr(11, 8);
        html = html.replaceAll('^^^duration^^^', diffString);
        html = html.replaceAll('^^^id^^^', id);
        return html;
    }

    function getTasksHeaderHtml(dateString, dayTasksDuration) {
        let html = document.getElementById('template-task-header').innerHTML;
        html = html.replaceAll('^^^date-string^^^', dateString);
        html = html.replaceAll('^^^duration-day^^^', dayTasksDuration);
        return html;
    }

    function collapseTasks(element) {
        let groupElement = element.closest(".task-group");
        let groupTasksElement = groupElement.nextElementSibling;
        groupTasksElement.classList.toggle("collapsed");
    }

    function loadMoreTasks() {
        let minId = Math.min.apply(Math, Object.keys(tracker.tasks));
        let lastDate = tracker.tasks[minId].date;
        $.ajax({
            url: '/tracker/load-more',
            type: 'post',
            data: {
                'date': lastDate,
            },
            dataType: "json",
        })
            .success(function (resultData) {
                if (resultData.status.code !== 1) {
                    alert(resultData.status.message);
                    return;
                }
                if (resultData.data.length === 0) {
                    document.getElementById('load-more').disabled = true;
                    return;
                }

                let tasks = resultData.data;
                let tasksSample, taskDate;
                Object.keys(tasks).forEach(function(key) {
                    let task = Object.assign({},tasks[key]);
                    tasksSample = Object.assign({}, tracker.default);
                    tasksSample.id = task.id;
                    tasksSample.name = task.name;
                    tasksSample.start = new Date(task.time_start);
                    if (task.time_finish) {
                        tasksSample.finish = new Date(task.time_finish);
                        tasksSample.duration = tasksSample.finish - tasksSample.start;
                    }
                    tasksSample.date = getTimeParts(tasksSample.start).date;
                    tasksSample.state = task.state_active;
                    tracker.tasks[tasksSample.id] = tasksSample;
                    taskDate = tasksSample.date;
                });
                updateSection(taskDate);
            });
    }
</script>
