<div id="popup-content" class="popup-content">
    <div class="row-fluid">
        <p class="block-heading">{{ $title }}</p>
        <div class="block-body">
            @yield('form_popup')
        </div>
    </div>
    <script type="text/javascript">
        $("body").ready(function(){
            var magnificPopup = $.magnificPopup.instance;
            var needRefresh = false;
            $("body").on("click", "#form_close", function(){
                magnificPopup.close();
                if(needRefresh)
                {
                    location.reload();
                }
            });
            var _class_success = 'span12 success';
            var _class_error = 'span12 error';

            function _clean_input_background_(form_object){
                $(form_object).each(function(){
                    $(this).attr('class','span12');
                });
            }
        });
    </script>
</div>