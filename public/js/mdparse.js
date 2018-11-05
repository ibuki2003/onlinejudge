function mdoverride(text){
    var renderer = new marked.Renderer()

    renderer.heading = function (text, level) {
        return `<h${level + 1}>${text}</h${level + 1}>`
    }
    return marked(text, {
        renderer: renderer,
        gfm: true,
        breaks: true,
    });
}

function renderMD(text, elm) {
    /*elm.find('code').each(function (i, e) {
        $(e).text(unsanitize($(e).text()));
    });
    */
    elm.html(mdoverride(unsanitize(text)));
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
