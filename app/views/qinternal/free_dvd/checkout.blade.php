<div id="popup-content" class="popup-content">
    <div class="row-fluid">
        <p class="block-heading">Checkout</p>
        <div class="block-body">
            <?= isset($html['html']) ? $html['html'] : '' ?>
        </div>
        
        <div class="block-body  __my_footer">
            <div class="btn-toolbar">
                <a class="btn _btn_print" target="_blank" href="<?= isset($url_print_checkout) ? $url_print_checkout.'/1' : ''; ?>">Print Box</a>
                <a class="btn _btn_print" target="_blank" href="<?= isset($url_print_checkout) ? $url_print_checkout : ''; ?>">Print</a>
                <button class="btn btn-primary _btn_finish" data-url="<?= isset($url_checkout) ? $url_checkout : ''; ?>" type="button">Finish</button>
                <span id="loading-form"></span>
            </div>
        </div>
    </div>
</div>

{{ HTML::script('/main/scripts/page/qinternal/free_dvd/main.js') }}

<style>
    .popup-content {
        width: 50%;
    }

    ._my_textarea {
        width: 98%;
        resize: vertical;
    }
    .__my_box {
        border: 1px solid #cccccc;
        padding: 4px;
        text-align: center;
        float: right;
        margin-right: 10px;
    }

    .__my_number {
        display: block;
        width: 100%;
        font-size: 20px;
        font-weight: bold;
        text-align: center;
        margin-top: 5px;
    }

    .block-body {
        overflow: hidden;
    }

    .__my_footer {
        margin: 0px;
        padding: 1em;
        background: #ededed;
    }
</style>