$(function(){
    $('.delete-list').click(function(){
        $('#myModal').data('deletelink',$(this).data('deletelink'));
    });
    
    $('#myModal #btnDelete').click(function(){
        window.location = $('#myModal').data('deletelink');
    });
});