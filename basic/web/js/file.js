var selectColor = '#ffa';
var focusColor = '#65d265';
var unfocused =true;
$("#mytable td").mouseover(function() {
    if(unfocused){
        var tds = $( this ).parent().find("td"),
        index = $.inArray( this, tds );
        $("#mytable td:nth-child("+( index + 1 )+")").css("background-color", selectColor);
        $(tds).css("background-color", selectColor);
    }
}).mouseout(function() {
    if(unfocused){
        var tds = $( this ).parent().find("td"),
        index = $.inArray( this, tds );
        $(tds).css("background-color", "#fff");
        $("#mytable td:nth-child("+( index + 1 )+")").css("background-color", "#fff");
    }
});

$("#mytable td").focus(function() {
    unfocused = false;
    var tds = $( this ).parent().find("td"),
    index = $.inArray( this, tds );
    $("#mytable td:nth-child("+( index + 1 )+")").css("background-color", focusColor);
    $(tds).css("background-color", focusColor);
}).blur(function() {
    unfocused = true;
    var tds = $( this ).parent().find("td"),
    index = $.inArray( this, tds );
    
    $(tds).css("background-color", "#fff");
    $("#mytable td:nth-child("+( index + 1 )+")").css("background-color", "#fff");
});


$("#mytable td").keydown(function(e) {
    var tds = $( this ).parent().find("td");
    index = $.inArray( this, tds );
    var thisTR = $( this ).parent();
    var trs = $( thisTR ).parent().find("tr"),
    indexTR = $.inArray( thisTR[0], trs );
    // left
    if (e.keyCode == 37){
        $(tds[index - 1]).focus();
    }
    // up 
    if (e.keyCode == 38){
        $(trs[indexTR - 1]).find("td")[index].focus();
    } 
    // right
    if (e.keyCode == 39){
        $(tds[index + 1]).focus();
    } 
    // down
    if (e.keyCode == 40){
        $(trs[indexTR + 1]).find("td")[index].focus();
    } 
    // esc
    if (e.keyCode == 27){
        $(this).blur();
    } 
})

// function lightFocus(e){
//     var tds = $( this ).parent().find("td");
//     index = $.inArray( this, tds );
//     var thisTR = $( this ).parent();
//     var trs = $( thisTR ).parent().find("tr"),
//     indexTR = $.inArray( thisTR[0], trs );
//     // left
//     if (e.keyCode == 37){
//         $(tds[index - 1]).focus();
//     }
//     // up 
//     if (e.keyCode == 38){
//         $(trs[indexTR - 1]).find("td")[index].focus();
//     } 
//     // right
//     if (e.keyCode == 39){
//         $(tds[index + 1]).focus();
//     } 
//     // down
//     if (e.keyCode == 40){
//         $(trs[indexTR + 1]).find("td")[index].focus();
//     } 
//     // esc
//     if (e.keyCode == 27){
//         $(this).blur();
//     } 
// }
// function lightOn(){
//     var tds = $( this ).parent().find("td"),
//     index = $.inArray( this, tds );
//     $("#mytable td:nth-child("+( index + 1 )+")").css("background-color", focusColor);
//     $(tds).css("background-color", focusColor);
// }

