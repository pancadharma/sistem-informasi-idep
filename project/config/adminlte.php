<?php


return [
    'title' => 'IDEP Foundation',
    'title_prefix' => '- ',
    'title_postfix' => ' -',

    'use_ico_only' => false,
    'use_full_favicon' => true,
    'google_fonts' => [
        'allowed' => false,
    ],
    'logo' => '<b>IDEP </b>Foundation',
    'logo_img' => '/vendor/adminlte/dist/img/idep.png',
    'logo_img_class' => 'brand-image img-circle elevation-3',
    'logo_img_xl' => null,
    'logo_img_xl_class' => 'brand-image-xs',
    'logo_img_alt' => 'IDEP Foundation Admin Logo',
    'auth_logo' => [
        'enabled' => true,
        'img' => [
            'path' => '/vendor/adminlte/dist/img/idep.png',
            'alt' => 'IDEP Foundation Logo',
            'class' => '',
            'width' => 50,
            'height' => 50,
        ],
    ],
    'preloader' => [
        'enabled' => true,
        'mode' => 'fullscreen',
        'img' => [
            'path' => '/vendor/adminlte/dist/img/idep.png',
            'alt' => 'IDEP Foundation Preloader Image',
            'effect' => 'animation__shake',
            'width' => 120,
            'height' => 120,
        ],
    ],
    'usermenu_enabled' => true,
    'usermenu_header' => true,
    'usermenu_header_class' => 'bg-primary',
    'usermenu_image' => true,
    'usermenu_desc' => true,
    'usermenu_profile_url' => true,
    'layout_topnav' => null,
    'layout_boxed' => null,
    'layout_fixed_sidebar' => true,
    'layout_fixed_navbar' => true,
    'layout_fixed_footer' => null,
    'layout_dark_mode' => null,
    'classes_auth_card' => 'card-outline card-primary',
    'classes_auth_header' => '',
    'classes_auth_body' => '',
    'classes_auth_footer' => '',
    'classes_auth_icon' => '',
    'classes_auth_btn' => 'btn-flat btn-primary',
    'classes_body' => '',
    'classes_brand' => '',
    'classes_brand_text' => '',
    'classes_content_wrapper' => '',
    'classes_content_header' => '',
    'classes_content' => '',
    'classes_sidebar' => 'sidebar-dark-primary elevation-4',
    'classes_sidebar_nav' => 'nav-child-indent',
    'classes_topnav' => 'navbar-white navbar-light',
    'classes_topnav_nav' => 'navbar-expand',
    'classes_topnav_container' => 'container',
    'sidebar_mini' => 'lg',
    'sidebar_collapse' => false,
    'sidebar_collapse_auto_size' => false,
    'sidebar_collapse_remember' => false,
    'sidebar_collapse_remember_no_transition' => true,
    'sidebar_scrollbar_theme' => 'os-theme-light',
    'sidebar_scrollbar_auto_hide' => 'l',
    'sidebar_nav_accordion' => false,
    'sidebar_nav_animation_speed' => 300,
    'right_sidebar' => false,
    'right_sidebar_icon' => 'fas fa-cogs',
    'right_sidebar_theme' => 'dark',
    'right_sidebar_slide' => true,
    'right_sidebar_push' => true,
    'right_sidebar_scrollbar_theme' => 'os-theme-light',
    'right_sidebar_scrollbar_auto_hide' => 'l',
    /*
    |--------------------------------------------------------------------------
    | URLs
    |--------------------------------------------------------------------------
    */
    'use_route_url' => false,
    'dashboard_url' => 'home',
    'logout_url' => 'logout',
    'login_url' => 'login',
    'register_url' => 'register',
    'password_reset_url' => 'password/reset',
    'password_email_url' => 'password/email',
    'profile_url' => true,

    'enabled_laravel_mix' => false,
    'laravel_mix_css_path' => 'css/app.css',
    'laravel_mix_js_path' => 'js/app.js',

    /*
    |--------------------------------------------------------------------------
    | Menu Items
    |--------------------------------------------------------------------------
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Menu-Configuration
    |
    */

    'menu' => [
        // Navbar items:
        // [
        //     'text' => 'Dashboard',
        //     'type' => 'user-panel',
        //     'url' => 'home',
        //     'class' => 'img-circle elevation-2',
        //     // 'active' => ['home', 'content', 'content*', 'regex:@^content/[0-9]+$@']
        // ],
        [
            'text' => 'User Profile',
            'url' => 'user/profile',
            'icon' => 'fas fa-fw fa-user',
            'icon_color' => 'primary',
        ],
        [
            'type' => 'navbar-search',
            'text' => 'search',
            'topnav_right' => false,
        ],
        [
            'type' => 'darkmode-widget',
            'icon' => 'fas fa-moon',
            'icon_enabled' => 'fas fa-moon',
            'icon_disabled' => 'far fa-moon',
            'color_enabled' => 'primary',
            'color_disabled' => 'info',
            'topnav_right' => true,
        ],
        [
            'type' => 'fullscreen-widget',
            'topnav_right' => false,
        ],

        // Sidebar items:
        [
            'type' => 'sidebar-menu-search',
            'text' => 'search',
        ],
        [
            'text' => 'Dashboard',
            'url' => 'home',
            'icon' => 'fas fa-tachometer-alt',
            'active' => ['home', 'dashboard', 'home*', 'regex:@^home/[0-9]+$@']
        ],
        [
            'header' => 'Master Data',
            'classes' => 'text-bold text-uppercase nav-header-pad-as-first',
        ],
        [
            'text'        => 'Setup',
            'icon'        => 'fas fa-cog',
            'label_color' => 'success',
            'classes'     => 'text-bold text-uppercase nav-header-pad-as-first',
        ],
        [
            'text' => 'Regional',
            'icon' => 'fas fa-copy',
            'classes'   => 'text-bold',
            'submenu'   =>  [
                [
                    'text'      => 'provinsi',
                    'active'    => ['provinsi', 'regex:@^provinsi/[0-9]+$@', 'country', 'provinsi*','country*'],
                    'route'     => 'provinsi.index',
                    'icon'      => 'fas fa-paste',
                    // 'can'       => ['provinsi_access','provinsi_edit', 'provinsi_show', 'provinsi_create'],
                ],
                [
                    'text'      => 'kabupaten',
                    'active'    => ['kabupaten', 'kabupaten*', 'regex:@^kabupaten/[0-9]+$@'],
                    'route'     => 'kabupaten.index',
                    'icon'      => 'fas fa-table',
                    // 'classes'   => 'text-danger text-uppercase',
                    // 'can'       => ['kabupaten_access','kabupaten_edit', 'kabupaten_show', 'kabupaten_create'],
                ],
                [
                    'text'      => 'kecamatan',
                    'active'    => ['kecamatan', 'kecamatan*','regex:@^kecamatan/[0-9]+$@'],
                    'route'     => 'kecamatan.index',
                    'icon'      => 'far fa-plus-square',
                    // 'can'       => ['kecamatan_access','kecamatan_edit', 'kecamatan_show', 'kecamatan_create'],
                    // 'classes'   => 'text-danger text-uppercase',
                ],
                [
                    'text'      => 'desa',
                    'active'    => ['desa', 'regex:@^desa/[0-9]+$@', 'desa*'],
                    'route'     => 'desa.index',
                    'icon'      => 'fas fa-map',
                    // 'can'   => ['desa_access','desa_edit', 'desa_show', 'desa_create'],
                    // 'classes' => 'text-danger text-uppercase',
                    // 'route' => '',
                ],
                [
                    'text'      => 'dusun',
                    'active'    => ['dusun', 'regex:@^dusun/[0-9]+$@', 'dusun*'],
                    'icon'      => 'fas fa-table',
                    'route'     => 'dusun.index',
                    'can'       => ['dusun_access','dusun_edit', 'dusun_show', 'dusun_create'],
                    // 'url'       => '#',
                    // 'can'       => ['_access','_edit', '_show', '_create'],
                ],
            ],
        ],
        [
            'text'    => 'user_management',
            'classes'   => 'text-bold',
            'icon'   => 'far fa-image',
            'submenu' => [
                [
                    'text'  => 'role',
                    'url'   => 'role',
                    'can'   => ['role_create',],
                    'icon' => 'far fa-check-circle',
                    'active' => ['role', 'role*', 'regex:@^role/[0-9]+$@'],
                    // 'route' => 'home',
                    // 'route' => ['admin.profile', ['userID' => '673']],
                    // 'can'       => ['_access','_edit', '_show', '_create'],
                ],
                [
                    'text'  => 'roles',
                    'url'   => 'jabatan',
                    // 'can' => 'view_jabatan',
                    // 'route' => ['admin.profile', ['userID' => '673']],
                    'icon' => 'fas fa-user-tie',
                    'active' => ['jabatan', 'jabatan*', 'regex:@^jabatan/[0-9]+$@'],
                ],
                [
                    'text'      => 'user',
                    'url'       => 'users',
                    // 'can'       => ['user_access','user_edit', 'user_show', 'user_create'],
                    'can'       => ['user_access'],
                    'route'     => 'users.index',
                    'icon'      => 'fas fa-users',
                    'active'    => ['users', 'users*', 'regex:@^users/[0-9]+$@'],
                ],
            ],
        ],
        [
            'text' => 'setup',
            'icon' => 'far fa-image',
            'submenu' => [
                [
                    'text' => 'kelompok_rentan',
                    //'url' => '#',
                    'route' => 'kelompokmarjinal.index',
                    'icon' => '',
                    'active' => ['kelompok_rentan', 'kelompok_rentan*', 'regex:@^kelompok_rentan/[0-9]+$@'],
                ],
                [
                    'text' => 'kategoripendonor',
                    // 'url' => '#',
                    'route' => 'kategoripendonor.index',
                    'icon' => '',
                    'active' => ['kategoripendonor', 'kategoripendonor*', 'regex:@^kategoripendonor/[0-9]+$@'],
                ],
                [
                    'text' => 'pendonor',
                    'url' => '#',
                    'icon' => '',
                    'active' => ['pendonor', 'pendonor*', 'regex:@^pendonor/[0-9]+$@'],
                ],
                [
                    'text' => 'partner',
                    'url' => '#',
                    'icon' => '',
                    'active' => ['partner', 'partner*', 'regex:@^partner/[0-9]+$@'],
                ],
                [
                    'text' => 'jenis_bantuan',
                    // 'url' => '#',
                    'route' => 'jenisbantuan.index',
                    'icon' => '',
                    'active' => ['jenis_bantuan', 'jenis_bantuan*', 'regex:@^jenis_bantuan/[0-9]+$@'],
                ],
                [
                    'text' => 'satuan',
                    'url' => '#',
                    'icon' => '',
                    'active' => ['satuan', 'satuan*', 'regex:@^satuan/[0-9]+$@'],
                ],
                [
                    'text' => 'sdg',
                    'url' => '#',
                    'icon' => '',
                    'active' => ['sdg', 'sdg*', 'regex:@^sdg/[0-9]+$@'],
                ],
                [
                    'text' => 'idep',
                    'url' => '#',
                    'icon' => '',
                    'active' => ['idep', 'idep*', 'regex:@^idep/[0-9]+$@'],
                ],
            ],
        ],
        [
            'text' => 'transaksi',
            'icon' => 'fas fa-file-invoice',
            'submenu' => [
                [
                    'text' => 'program',
                    'icon' => 'nav-icon fas fa-project-diagram',
                    // 'route' => 'program',
                    'url' => 'program',
                    
                    'active' => ['program', 'program*', 'regex:@^program/[0-9]+$@'],
                ],
                [
                    'text' => 'kegiatan',
                    'icon' => 'nav-icon fa fa-user-graduate',                
                    // 'route' => 'kegiatan',
                    'url' => 'kegiatan',
                    'active' => ['kegiatan', 'kegiatan*', 'regex:@^kegiatan/[0-9]+$@'],
                ],
            ],
        ],
        [
            'text' => 'evaluasi',
            'icon' => 'fas fa-tachometer-alt',
            // 'url' => '#',
            'active' => ['evaluasi', 'evaluasi*', 'regex:@^evaluasi/[0-9]+$@'],
            'submenu' => [
                [
                    'url' => '#',
                    'text' => 'Hehe',
                ],
                [
                    'url' => '#',
                    'text' => 'Hehe',
                ],
            ],
        ],
        [
            'text' => 'log',
            'url' => '#',
            'icon' => 'fas fa-file-contract',
            'icon_color' => 'yellow',
            'active' => ['log', 'log*', 'log:@^evaluasi/[0-9]+$@'],
        ],
        [
            'header' => 'laporan',
            'classes' => 'text-bold text-uppercase',
        ],
        [
            'text' => 'laporan',
            'url' => '#',
            'icon' => 'fas fa-file',
            'icon_color' => 'cyan',
            'active' => ['laporan', 'laporan*', 'log:@^laporan/[0-9]+$@'],
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Menu Filters
    |--------------------------------------------------------------------------
    |
    | Here we can modify the menu filters of the admin panel.
    |
    | For detailed instructions you can look the menu filters section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Menu-Configuration
    |
    */

    'filters' => [
        JeroenNoten\LaravelAdminLte\Menu\Filters\GateFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\HrefFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\SearchFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\ActiveFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\ClassesFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\LangFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\DataFilter::class,
    ],

    /*
    |--------------------------------------------------------------------------
    | Plugins Initialization
    |--------------------------------------------------------------------------
    |
    | Here we can modify the plugins used inside the admin panel.
    |
    | For detailed instructions you can look the plugins section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Plugins-Configuration
    |
    */

    'plugins' => [
        'Datatables' => [
            'active' => false,
            'files' => [
                [
                    'type' => 'js',
                    'asset' => true,
                    'location' => '/vendor/datatables/js/jquery.dataTables.min.js',
                ],
                [
                    'type' => 'js',
                    'asset' => true,
                    'location' => '/vendor/datatables/js/dataTables.bootstrap4.min.js',
                ],
                [
                    'type' => 'css',
                    'asset' => true,
                    'location' => '/vendor/datatables/css/dataTables.bootstrap4.min.css',
                ],
            ],
        ],
        'DatatablesNew' => [
            'active' => false,
            'files' => [
                [
                    'type' => 'js',
                    'asset' => true,
                    'location' => '/vendor/datatables-new/datatables.min.js',
                ],
                [
                    'type' => 'js',
                    'asset' => true,
                    'location' => '/vendor/datatables-new/pdfmake.min.js',
                ],
                [
                    'type' => 'js',
                    'asset' => true,
                    'location' => '/vendor/datatables-new/vfs_fonts.js',
                ],
                [
                    'type' => 'css',
                    'asset' => true,
                    'location' => '/vendor/datatables-new/datatables.min.css',
                ],
            ],
        ],
        'SelectDatatables' => [
            'active' => false,
            'files' => [
                [
                    'type' => 'js',
                    'asset' => true,
                    'location' => '/vendor/datatables-plugins/select/js/dataTables.select.js',
                ],
                [
                    'type' => 'js',
                    'asset' => true,
                    'location' => '/vendor/datatables-plugins/select/js/select.dataTables.js',
                ],
                [
                    'type' => 'css',
                    'asset' => true,
                    'location' => '/vendor/datatables-plugins/select/css/select.bootstrap4.min.css',
                ],
            ],
        ],
        'Validation' => [
            'active' => false,
            'files' => [
                [
                    'type' => 'js',
                    'asset' => true,
                    'location' => '/vendor/jquery-validation/jquery.validate.min.js',
                ],
                [
                    'type' => 'js',
                    'asset' => true,
                    'location' => '/vendor/jquery-validation/additional-methods.min.js',
                ],
            ],
        ],
        'Select2' => [
            'active' => false,
            'files' => [
                [
                    'type' => 'js',
                    'asset' => true,
                    // 'location' => '//cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js',
                    'location' => '/vendor/select2/js/select2.min.js',
                ],
                [
                    'type' => 'css',
                    'asset' => true,
                    // 'location' => '//cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.css',
                    'location' => '/vendor/select2/css/select2.css',
                ],
            ],
        ],
        'Chartjs' => [
            'active' => false,
            'files' => [
                [
                    'type' => 'js',
                    'asset' => true,
                    'location' => '/vendor/chart.js/Chart.bundle.min.js',
                ],
            ],
        ],
        'Sweetalert2' => [
            'active' => false,
            'files' => [
                [
                    'type' => 'css',
                    'asset' => true,
                    // 'location' => '//cdn.jsdelivr.net/npm/sweetalert2@11.12.1/dist/sweetalert2.min.css',
                    'location' => '/vendor/sweetalert2/sweetalert2.min.css',
                ],
                [
                    'type' => 'js',
                    'asset' => true,
                    // 'location' => '//cdn.jsdelivr.net/npm/sweetalert2@11.12.1/dist/sweetalert2.all.min.js',
                    'location' => '/vendor/sweetalert2/latest.js',
                ],
            ],
        ],
        'Pace' => [
            'active' => false,
            'files' => [
                [
                    'type' => 'css',
                    'asset' => true,
                    'location' => '//cdnjs.cloudflare.com/ajax/libs/pace/1.0.2/themes/blue/pace-theme-center-radar.min.css',
                ],
                [
                    'type' => 'js',
                    'asset' => true,
                    'location' => '//cdnjs.cloudflare.com/ajax/libs/pace/1.0.2/pace.min.js',
                ],
            ],
        ],
        'DateRangePicker' => [
            'active' => false,
            'files' => [
                [
                    'type' => 'js',
                    'asset' => true,
                    'location' => 'vendor/moment/moment.min.js',
                ],
                [
                    'type' => 'js',
                    'asset' => true,
                    'location' => 'vendor/daterangepicker/daterangepicker.js',
                ],
                [
                    'type' => 'css',
                    'asset' => true,
                    'location' => 'vendor/daterangepicker/daterangepicker.css',
                ],
            ],
        ],
        'DatatablesPlugins' => [
            'active' => false,
            'files' => [
                [
                    'type' => 'js',
                    'asset' => true,
                    'location' => 'vendor/datatables-plugins/buttons/js/dataTables.buttons.min.js',
                ],
                [
                    'type' => 'js',
                    'asset' => true,
                    'location' => 'vendor/datatables-plugins/buttons/js/buttons.bootstrap4.min.js',
                ],
                [
                    'type' => 'js',
                    'asset' => true,
                    'location' => 'vendor/datatables-plugins/buttons/js/buttons.html5.min.js',
                ],
                [
                    'type' => 'js',
                    'asset' => true,
                    'location' => 'vendor/datatables-plugins/buttons/js/buttons.print.min.js',
                ],
                [
                    'type' => 'js',
                    'asset' => true,
                    'location' => 'vendor/datatables-plugins/jszip/jszip.min.js',
                ],
                [
                    'type' => 'js',
                    'asset' => true,
                    'location' => 'vendor/datatables-plugins/pdfmake/pdfmake.min.js',
                ],
                [
                    'type' => 'js',
                    'asset' => true,
                    'location' => 'vendor/datatables-plugins/pdfmake/vfs_fonts.js',
                ],
                [
                    'type' => 'css',
                    'asset' => true,
                    'location' => 'vendor/datatables-plugins/buttons/css/buttons.bootstrap4.min.css',
                ],
            ],
        ],
        'Summernote' => [
            'active' => false,
            'files' => [
                [
                    'type' => 'js',
                    'asset' => true,
                    'location' => 'vendor/summernote/summernote-bs4.min.js',
                ],
                [
                    'type' => 'css',
                    'asset' => true,
                    'location' => 'vendor/summernote/summernote-bs4.min.css',
                ],
            ],
        ],
        'Tabulator' => [
            'active' => false,
            'files' => [
                [
                    'type' => 'js',
                    'asset' => true,
                    'location' => 'vendor/tabulator-tables/dist/js/tabulator.min.js',
                ],
                [
                    'type' => 'css',
                    'asset' => true,
                    'location' => 'vendor/tabulator-tables/dist/css/tabulator_semanticui.min.css',
                ],
            ],
        ],
        'BootstrapSelect' => [
            'active' => false,
            'files' => [
                [
                    'type' => 'js',
                    'asset' => true,
                    'location' => 'vendor/bootstrap-select/dist/js/bootstrap-select.min.js',
                ],
                [
                    'type' => 'css',
                    'asset' => true,
                    'location' => 'vendor/bootstrap-select/dist/css/bootstrap-select.min.css',
                ],
            ],
        ],
        'Toastr' => [
            'active' => false,
            'files' => [
                [
                    'type' => 'js',
                    'asset' => true,
                    'location' => 'vendor/toastr/toastr.min.js',
                ],
                [
                    'type' => 'css',
                    'asset' => true,
                    'location' => 'vendor/toastr/toastr.css',
                ],
            ],
        ],
        'BootstrapSwitch' => [
            'active' => false,
            'files' => [
                [
                    'type' => 'js',
                    'asset' => true,
                    'location' => 'vendor/bootstrap-switch/js/bootstrap-switch.min.js',
                ],
            ],
        ],
        'KrajeeFileinput' => [
            'active' => false,
            'files' => [
                [
                    'type' => 'css',
                    'asset' => true,
                    'location' => 'vendor/krajee-fileinput/css/fileinput.min.css',
                ],
                [
                    'type' => 'css',
                    'asset' => true,
                    'location' => 'vendor/krajee-fileinput/themes/explorer-fa5/theme.min.css',
                ],
                [
                    'type' => 'js',
                    'asset' => true,
                    'location' => 'vendor/krajee-fileinput/js/fileinput.min.js',
                ],
                [
                    'type' => 'js',
                    'asset' => true,
                    'location' => 'vendor/krajee-fileinput/themes/fa5/theme.min.js',
                ],
                [
                    'type' => 'js',
                    'asset' => true,
                    'location' => 'vendor/krajee-fileinput/themes/explorer-fa5/theme.min.js',
                ],
                [
                    'type' => 'js',
                    'asset' => true,
                    'location' => 'vendor/krajee-fileinput/js/locales/en.js',
                ],
            ],
        ],


    ],

    /*
    |--------------------------------------------------------------------------
    | IFrame
    |--------------------------------------------------------------------------
    |
    | Here we change the IFrame mode configuration. Note these changes will
    | only apply to the view that extends and enable the IFrame mode.
    |
    | For detailed instructions you can look the iframe mode section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/IFrame-Mode-Configuration
    |
    */

    'iframe' => [
        'default_tab' => [
            'url' => null,
            'title' => null,
        ],
        'buttons' => [
            'close' => true,
            'close_all' => true,
            'close_all_other' => true,
            'scroll_left' => true,
            'scroll_right' => true,
            'fullscreen' => true,
        ],
        'options' => [
            'loading_screen' => 1000,
            'auto_show_new_tab' => true,
            'use_navbar_items' => true,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Livewire
    |--------------------------------------------------------------------------
    |
    | Here we can enable the Livewire support.
    |
    | For detailed instructions you can look the livewire here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Other-Configuration
    |
    */

    'livewire' => false,
];
