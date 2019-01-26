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
    drawAcceptionUserChart('acception_user');
}

function getAggregate(dataTable, params, columnClass){
    return $.ajax({
        type: "GET",
        url: '/api/aggregate',
        data: params,
        dataType: "json",
        success: function(jsondata){
            for(var row of jsondata){
                dataTable.addRow([columnClass(row[params.each]),Number(row.count)]);
            }
        },
        error: function(error){
            console.error(error);
        }
    });
}

function drawProblemCreatorChart(target_id){
    // データの準備
    var data = new google.visualization.DataTable();
    data.addColumn('string', 'Creator');
    data.addColumn('number', 'count');

    var df = $.Deferred();
    getAggregate(data, {
        'for': 'problems',
        'each': 'user_id',
        'count': '*',
        'order': 'desc',
        'limit': 5,
        'remain': '',
    }, String).done(function(){df.resolve();});
    df.done(function(){
        // オプションの準備
        var options = {
            title: 'Problem Creator',
            vAxis: {
                title: 'User id',
            },
            hAxis: {
                title: 'problem count',
                minValue: 0,
                format: '#',
            },
        };

        // 描画用インスタンスの生成および描画メソッドの呼び出し
        //var chart = new google.visualization.PieChart(document.getElementById(target_id));
        var chart = new google.visualization.BarChart(document.getElementById(target_id));
        
        chart.draw(data, options);
    });
}

function drawProblemDifficultyChart(target_id){
    // データの準備
    var data = new google.visualization.DataTable();
    data.addColumn('number', 'Difficulty');
    data.addColumn('number', 'count');

    var df = $.Deferred();

    getAggregate(data, {
        'for': 'problems',
        'each': 'difficulty',
        'count': '*',
        //'map': '',
    }, Number).done(function(){df.resolve();});
    df.done(function(){
        // オプションの準備
        var options = {
            title: 'Problem Difficulty',
            hAxis: {
                title: 'difficulty',
            },
            vAxis: {
                title: 'problem count',
                format: '#',
            },
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

    getAggregate(data, {
        'for': 'submissions',
        'each': 'status',
        'count': '*',
        'order': 'desc',
        'limit': 5,
        'remain': '',
    }, String).done(function(){df.resolve();});
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

    getAggregate(data, {
        'for': 'submissions',
        'each': 'lang_id',
        'count': '*',
        'order': 'desc',
        'limit': 5,
        'remain': '',
    }, String).done(function(){df.resolve();});

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

function drawAcceptionUserChart(target_id){
    // データの準備
    var data = new google.visualization.DataTable();
    data.addColumn('string', 'Sender');
    data.addColumn('number', 'count');

    var df = $.Deferred();

    getAggregate(data, {
        'for': 'submissions',
        'each': 'user_id',
        'count': '*',
        'order': 'desc',
        'filter': '(status:AC)',
        'limit': 5,
    }, String).done(function(){df.resolve();});
    df.done(function(){
        // オプションの準備
        var options = {
            title: 'Problem Unique Acception Ranking',
            vAxis: {
                title: 'User id',
            },
            hAxis: {
                title: 'problem count',
                minValue: 0,
                format: '#',
            },
        };

        // 描画用インスタンスの生成および描画メソッドの呼び出し
        var chart = new google.visualization.BarChart(document.getElementById(target_id));
        chart.draw(data, options);
    });
}
