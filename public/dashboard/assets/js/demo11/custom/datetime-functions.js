function ChangeFormateDate(oldDate){
    var p = oldDate.split(/\D/g);
    return [p[2],p[0],p[1] ].join("-");
 }