function getTimeStr(){
    var date = new Date();         // 現在日時を生成
    var ret = ( '000' + date.getFullYear() ).slice( -4 ) + '/' +
        ( '0' + (date.getMonth()+1) ).slice( -2 ) + '/' +
        ( '0' + date.getDate() ).slice( -2 ) + ' ' +
        ( '0' + date.getHours() ).slice( -2 ) + ':' +
        ( '0' + date.getMinutes() ).slice( -2 ) + ':' +
        ( '0' + date.getSeconds() ).slice( -2 );

    return ret;
};

jQuery.preloadImages = function(){
    for(var i = 0; i<arguments.length; i++){
        jQuery("<img>").attr("src", arguments[i]);
    }
};

function autoreload(url, table, prevbtn, nextbtn, staticon, updatetime, columns, interval, filterfunc, rowCallback){
    $.preloadImages("/img/check.svg","/img/error.svg","/img/spinner.svg");
    var page=1;

    prevbtn.on('click',function(){page--;load();});
    nextbtn.on('click',function(){page++;load();});

    setInterval(load,interval);
    load();

    function load(){
        staticon.removeClass('error done').addClass('loading');

        param={page: page};

        $.ajax({
            type: "GET",
            url: url,
            data: Object.assign(param, filterfunc()),
            dataType: "json",
            timeout: 1000, // 1 second
            success: function(data){
                update(data);
                staticon.removeClass('loading').addClass('done');
            },
            error: function(data){
                console.error(data);
                staticon.removeClass('loading').addClass('error');
            }
        });
    }

    function update(response){
        lastdata=response;

        if(!response.links.prev || page<=1)
            prevbtn.attr('disabled', true);
        else{
            prevbtn.attr('disabled', false);
        }

        if(!response.links.next)
            nextbtn.attr('disabled', true);
        else{
            nextbtn.attr('disabled', false);
        }

        table.empty();
        for (var data of response['data']) {
            var row = $('<tr>');
            if(rowCallback)rowCallback(row,data);
            for(column of columns){
                var cell=$('<td>');
                if(typeof column === 'string' || column instanceof String){
                    cell.text(data[column]);
                }else if(typeof column == 'function'){
                    cell.html(column(data));
                }
                row.append(cell);
            }
            table.append(row);
        }
        updatetime.text("最終更新:"+getTimeStr());
    }
}
