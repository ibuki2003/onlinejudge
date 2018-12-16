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
        url: '/api/statistics/problem_creator',
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
            title: 'Problem Creators',
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
        url: '/api/statistics/problem_difficulty',
        dataType: "json",
        success: function(jsondata){
            for(var key = 0; key < jsondata.length; key++) {
                data.addRow([key+1,jsondata[key]]);
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
            title: 'Problem Difficulties',
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
        url: '/api/statistics/submission_status',
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
        url: '/api/statistics/submission_lang',
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
            title: 'Submission Langs',
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
        url: '/api/statistics/submission_user',
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
