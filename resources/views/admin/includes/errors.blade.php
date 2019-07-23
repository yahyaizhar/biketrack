@if (count($errors) > 0)
    @foreach ($errors->all() as $error)
        <div class="kt-section__content">
            <div class="alert alert-danger fade show" role="alert">
                <div class="alert-icon"><i class="flaticon2-close-cross"></i></div>
                <div class="alert-text">{{ $error }}</div>
                <div class="alert-close">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true"><i class="la la-close"></i></span>
                    </button>
                </div>
            </div>
        </div>
    @endforeach
@endif