/*
Set click event listener for non-anchor tag.
depends:JQuery
usage: $('selector').flexible_link('href')
 */
jQuery.fn.flexible_link = function(){
    jQuery(this).each(function(i,e){
        var elm=$(e);
        if(elm.attr('data-original-href')===undefined){
            elm.attr('data-original-href', elm.attr('href'));
            elm.attr('href','');
            elm.on('click', flexible_link_listener);
        }
    });
    return this;
}


function flexible_link_listener(elm){
    var url=$(elm.currentTarget).attr('data-original-href');
    location.href=url;
}
