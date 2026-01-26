<footer id="footer" class="footer bg-base-0 position-fixed bottom-0 left-0 right-0 w-100{{ request()->is('admin/invoices/*') || request()->is('account/invoices/*') ? ' d-print-none' : '' }}">
    <div class="container py-5">
        
        <div class="row">
            <div class="col-12 col-lg order-2 order-lg-1">
                <div class="text-muted py-1">© {{ now()->year }} TomasDevs. Todos los derechos reservados.</div>
            </div>
            <div class="col-12 col-lg-auto order-1 order-lg-2 d-flex flex-column flex-lg-row">
                <div class="nav p-0 mx-n3 mb-3 mb-lg-0 d-flex flex-column flex-lg-row">
                    <div class="nav-item d-flex">
                        <a href="#" class="nav-link py-1 d-flex align-items-center text-secondary" id="dark-mode" data-tooltip="true" title="Cambiar tema">
                            @include('icons.contrast', ['class' => 'width-4 height-4 fill-current ' . (__('lang_dir') == 'rtl' ? 'ml-2' : 'mr-2')])
                            <span class="text-muted" data-text-light="Claro" data-text-dark="Oscuro">{{ (config('settings.dark_mode') == 1 ? 'Oscuro' : 'Claro') }}</span>
                        </a>
                    </div>

                    @if(count(config('app.locales')) > 1)
                        <div class="nav-item d-flex">
                            <a href="#" class="nav-link py-1 d-flex align-items-center text-secondary" data-toggle="modal" data-target="#change-language-modal" data-tooltip="true" title="Cambiar idioma">
                                @include('icons.language', ['class' => 'width-4 height-4 fill-current ' . (__('lang_dir') == 'rtl' ? 'ml-2' : 'mr-2')])
                                <span class="text-muted">{{ config('app.locales')[config('app.locale')]['name'] }}</span>
                            </a>
                        </div>

                        <div class="modal fade" id="change-language-modal" tabindex="-1" role="dialog" aria-labelledby="change-language-modal-label" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered" role="dialog">
                                <div class="modal-content border-0 shadow">
                                    <div class="modal-header">
                                        <h6 class="modal-title" id="change-language-modal-label">Cambiar idioma</h6>
                                        <button type="button" class="close d-flex align-items-center justify-content-center width-12 height-14" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true" class="d-flex align-items-center">@include('icons.close', ['class' => 'fill-current width-4 height-4'])</span>
                                        </button>
                                    </div>
                                    <form action="{{ route('locale') }}" method="post" enctype="multipart/form-data">
                                        @csrf
                                        <div class="modal-body">
                                            <div class="row">
                                                @foreach(config('app.locales') as $code => $language)
                                                    <div class="col-6">
                                                        <div class="custom-control custom-radio">
                                                            <input type="radio" id="i-language-{{ $code }}" name="locale" class="custom-control-input" value="{{ $code }}" @if(config('app.locale') == $code) checked @endif>
                                                            <label class="custom-control-label" for="i-language-{{ $code }}" lang="{{ $code }}">{{ $language['name'] }}</label>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                                            <button type="submit" class="btn btn-primary">Guardar</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    @include('shared.cookie-law')
</footer>
