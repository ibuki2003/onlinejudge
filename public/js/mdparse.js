function parseMD(md) {
   var html=marked(md, {
        gfm: true,
        breaks: true,
    });
    var elm=$('<div />').html(html);
    renderMathInElement(elm[0],{delimiters: [
        {left: "$", right: "$", display: false}
    ]});
    return elm.html();
}
