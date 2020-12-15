function toThaiDate(param) {
    var month = [];
    month['01'] = "มกราคม";
    month['02'] = "กุมภาพันธ์";
    month['03'] = "มีนาคม";
    month['04'] = "เมษายน";
    month['05'] = "พฤษภาคม";
    month['06'] = "มิถุนายน";
    month['07'] = "กรกฎาคม";
    month['08'] = "สิงหาคม";
    month['09'] = "กันยายน";
    month['10'] = "ตุลาคม";
    month['11'] = "พฤศจิกายน";
    month['12'] = "ธันวาคม";

    return month[param];
    
}