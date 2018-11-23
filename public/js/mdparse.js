function mdoverride(text){
    return marked(text, {
        gfm: true,
        breaks: true,
    });
}

function renderMD(text, elm, decode=true) {
    /*elm.find('code').each(function (i, e) {
        $(e).text(unsanitize($(e).text()));
    });
    */
   if(decode)text=unsanitize(text);
    elm.html(mdoverride(text));
    renderMathInElement(elm[0],{delimiters: [
        {left: "$", right: "$", display: false}
    ]});
}

function sanitize(html) {
    return $('<div />').text(html).html();
}

function unsanitize(html) {
    return $('<div />').html(html).text();
}

function parseTeX(html) {
    return html.replace(/\$(.+?)\$(?!\$)/g, '<tex>$1</tex>');
}
