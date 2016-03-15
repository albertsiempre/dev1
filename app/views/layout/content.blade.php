@section('content')
    @include('layout.sidebar')
    <div class="content">
        @if (isset($content_title) && $content_title !== false)
            <div class="header">
                <h1 class="page-title">{{ $content_title }}</h1>
            </div>
        @endif
        @include('layout.breadcrumb')
        <div class="modal small hide fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">X</button>
                <h3 id="deleteModalLabel">Delete Confirmation</h3>
            </div>
            <div class="modal-body">
                <p class="error-text"><i class="icon-warning-sign modal-icon"></i>Are you sure you want to delete <span>__NAME__</span>?</p>
            </div>
            <div class="modal-footer">
                <button class="btn" data-dismiss="modal" aria-hidden="true">Cancel</button>
                <button class="btn btn-danger" data-dismiss="modal" id="btnDelete">Delete</button>
            </div>
        </div>
        <div class="container-fluid">
            <div class="row-fluid">
                @include('layout.notifications')
            </div>
            <div class="row-fluid">
                <div class="row-fluid">
                    @yield('search_content')
                </div>
                <div class="row-fluid">
                    @yield('actual_content')
                </div>
                <footer>
                    <hr />
                    <p>&copy; 2013 Qeon Interactive</p>
                </footer>
            </div>
        </div>
    </div>
@stop