



$(function () {

    $('#tohide').click(function() {
        //console.log('#tohide clicked');
        $("#nav-frame").addClass("hideleft");
        $("#tohide").addClass("hide");
        $("#toshow").removeClass("hide");

        //
        $(".container-fluid.row #left-sidebar").removeClass("col-md-2");
        $(".container-fluid.row main").removeClass("col-md-10");
        $(".container-fluid.row main").addClass("col-md-12");
        
    });

    $('#toshow').click(function() {
        //console.log('#toshow clicked');
        $("#nav-frame").removeClass("hideleft");
        $("#toshow").addClass("hide");
        $("#tohide").removeClass("hide");

        //
        $(".container-fluid.row #left-sidebar").addClass("col-md-2");
        $(".container-fluid.row main").removeClass("col-md-12");
        $(".container-fluid.row main").addClass("col-md-10");

    });

});
