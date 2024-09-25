@inject('preloaderHelper', 'JeroenNoten\LaravelAdminLte\Helpers\PreloaderHelper')
<div class="{{ $preloaderHelper->makePreloaderClasses() }}" style="{{ $preloaderHelper->makePreloaderStyle() }}">
    @hasSection('preloader')
    {{-- Use a custom preloader content --}}
        @yield('preloader')
    @else
    {{-- Use the default preloader content --}}
    <img src="{{ asset(config('adminlte.preloader.img.path', '/vendor/adminlte/dist/img/idep.png')) }}"
             class="img-circle {{ config('adminlte.preloader.img.effect', 'animation__shake') }}"
             alt="{{ config('adminlte.preloader.img.alt', 'IDEP LOGO Preloader Image') }}"
             width="{{ config('adminlte.preloader.img.width', 120) }}"
             height="{{ config('adminlte.preloader.img.height', 120) }}"
             style="animation-iteration-count:infinite;">
    @endif
</div>
