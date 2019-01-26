$(function(){
    // パッケージのロード
    google.charts.load('current', {packages: ['corechart']});
    // コールバックの登録
    google.charts.setOnLoadCallback(drawChart);
});

function drawChart(){
    drawProblemCreatorChart('problem_creator');
    drawProblemDifficultyChart('problem_difficulty');
    drawSubmissionStatusChart('submission_status');
    drawSubmissionLangChart('submission_lang');
    drawSubmissionUserChart('submission_user');
}

function drawProblemCreatorChart(target_id){
    // データの準備
    var data = new google.visualization.DataTable();
    data.addColumn('string', 'Creator');
    data.addColumn('number', 'count');

    var df = $.Deferred();

    $.ajax({
        type: "GET",
        url: '/api/aggregate',
        data: {
            'for': 'problems',
            'each': 'user_id',
            'count': '*',
            'order': 'desc',
            'limit': 5,
            'remain': '',
        },
        dataType: "json",
        success: function(jsondata){
            for(var row of jsondata){
                console.debug(row);
                data.addRow([row.user_id,Number(row.count)]);
            }
            df.resolve();
        },
        error: function(error){
            console.log(error);
        }
    });

    df.done(function(){
        // オプションの準備
        var options = {
            title: 'Problem Creator',
        };

        // 描画用インスタンスの生成および描画メソッドの呼び出し
        var chart = new google.visualization.PieChart(document.getElementById(target_id));
        chart.draw(data, options);
    });
}

function drawProblemDifficultyChart(target_id){
    // データの準備
    var data = new google.visualization.DataTable();
    data.addColumn('number', 'Difficulty');
    data.addColumn('number', 'count');

    var df = $.Deferred();

    $.ajax({
        type: "GET",
        url: '/api/aggregate',
        data: {
            'for': 'problems',
            'each': 'difficulty',
            'count': '*',
            'map': '',
        },
        dataType: "json",
        success: function(jsondata){
            for(var key in jsondata) {
                data.addRow([Number(key),Number(jsondata[key])]);
            }
            df.resolve();
        },
        error: function(error){
            console.log(error);
        }
    });

    df.done(function(){
        // オプションの準備
        var options = {
            title: 'Problem Difficulty',
        };

        // 描画用インスタンスの生成および描画メソッドの呼び出し
        var chart = new google.visualization.ColumnChart(document.getElementById(target_id));
        chart.draw(data, options);
    });
}



function drawSubmissionStatusChart(target_id){
    // データの準備
    var data = new google.visualization.DataTable();
    data.addColumn('string', 'Status');
    data.addColumn('number', 'count');

    var df = $.Deferred();

    $.ajax({
        type: "GET",
        url: '/api/aggregate',
        data: {
            'for': 'submissions',
            'each': 'status',
            'count': '*',
            'order': 'desc',
            'limit': 5,
            'remain': '',
        },
        dataType: "json",
        success: function(jsondata){
            for(var row of jsondata){
                data.addRow([row.status,Number(row.count)]);
            }
            df.resolve();
        },
        error: function(error){
            console.log(error);
        }
    });

    df.done(function(){
        // オプションの準備
        var options = {
            title: 'Submission Status',
        };

        // 描画用インスタンスの生成および描画メソッドの呼び出し
        var chart = new google.visualization.PieChart(document.getElementById(target_id));
        chart.draw(data, options);
    });
}

function drawSubmissionLangChart(target_id){
    // データの準備
    var data = new google.visualization.DataTable();
    data.addColumn('string', 'Status');
    data.addColumn('number', 'count');

    var df = $.Deferred();

    $.ajax({
        type: "GET",
        url: '/api/aggregate',
        data: {
            'for': 'submissions',
            'each': 'lang_id',
            'count': '*',
            'order': 'desc',
            'limit': 5,
            'remain': '',
        },

        dataType: "json",
        success: function(jsondata){
            for(var row of jsondata){
                data.addRow([row.lang_id,Number(row.count)]);
            }
            df.resolve();
        },
        error: function(error){
            console.log(error);
        }
    });

    df.done(function(){
        // オプションの準備
        var options = {
            title: 'Submission Language',
        };

        // 描画用インスタンスの生成および描画メソッドの呼び出し
        var chart = new google.visualization.PieChart(document.getElementById(target_id));
        chart.draw(data, options);
    });
}

function drawSubmissionUserChart(target_id){
    // データの準備
    var data = new google.visualization.DataTable();
    data.addColumn('string', 'Sender');
    data.addColumn('number', 'count');

    var df = $.Deferred();

    $.ajax({
        type: "GET",
        url: '/api/aggregate',
        data: {
            'for': 'submissions',
            'each': 'user_id',
            'count': '*',
            'order': 'desc',
            'limit': 5,
            'remain': '',
        },
        dataType: "json",
        success: function(jsondata){
            for(var row of jsondata){
                data.addRow([row.user_id,Number(row.count)]);
            }
            df.resolve();
        },
        error: function(error){
            console.log(error);
        }
    });

    df.done(function(){
        // オプションの準備
        var options = {
            title: 'Submission Sender',
        };

        // 描画用インスタンスの生成および描画メソッドの呼び出し
        var chart = new google.visualization.PieChart(document.getElementById(target_id));
        chart.draw(data, options);
    });
}
