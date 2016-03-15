$("body").ready(function(){
    $("body").on("click", "#submit_posting", function(){
        var btn = $(this);
        var submit = $(this).data("urlsubmit");
        var pdf = $(this).data("urlpdf");
        $(this).attr("disabled", "disabled");
        var period = $(this).parents("tr").find("td:eq(1)").text().replace(":", "").trim();
        if(confirm("Anda yakin akan melakukan posting untuk Bulan " + period + "?"))
        {
            $.get(submit, function(res){
                var obj = $.parseJSON(res);
                if(obj.success == true)
                {
                    window.location.href = pdf;
                    btn.removeClass("btn-primary");
                } else {
                    btn.removeAttr("disabled");
                    alert(obj.msg);
                }
            });
        } else {
            btn.removeAttr("disabled");
        }
        return false;
    });
});