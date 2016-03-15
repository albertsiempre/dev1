@section('actual_content')
    <div name="paging"></div>
    <div class="well">
        <table class="table">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Nama Lengkap</th>
                    <th>Nama Warnet</th>
                    <th>Provinsi</th>
                    <th>Kota</th>
                    <th>Email</th>
                    <th>No.Telp / HP</th>
                    <th>Pesan</th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>
    <div name="paging"></div>
    {{ HTML::style('/main/libs/jquery.chosen/chosen.css') }}
    {{ HTML::script('/main/libs/jquery.chosen/chosen.jquery.min.js') }}
    {{ HTML::script('/main/scripts/page/warnet/main.js') }}
    {{ HTML::style('/main/libs/jquery.validate.min.js') }}
@stop