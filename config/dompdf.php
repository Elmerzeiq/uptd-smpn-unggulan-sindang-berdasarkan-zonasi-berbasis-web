<?php
// config/dompdf.php - Complete configuration to fix ImageMagick issues

return [
    'show_warnings' => false,
    'public_path' => public_path(),
    'orientation' => 'portrait',
    'defines' => [
        /**
         * Explicitly disable ImageMagick to prevent DLL errors
         */
        'DOMPDF_ENABLE_IMAGICK' => false,

        /**
         * Enable GD as the primary image processor
         */
        'DOMPDF_ENABLE_GD' => true,

        /**
         * Disable automatic font subsetting which can cause issues
         */
        'DOMPDF_ENABLE_FONT_SUBSETTING' => false,

        /**
         * Set memory limit for large PDFs
         */
        'DOMPDF_MEMORY_LIMIT' => '512M',
    ],

    'options' => [
        /**
         * Use DejaVu Sans which has better character support
         */
        'default_font' => 'DejaVu Sans',

        /**
         * Font directories
         */
        'font_dir' => storage_path('fonts/'),
        'font_cache' => storage_path('fonts/'),

        /**
         * Temporary directory
         */
        'temp_dir' => sys_get_temp_dir(),

        /**
         * Application root for security
         */
        'chroot' => realpath(base_path()),

        /**
         * Enable PHP for Blade template support
         */
        'isPhpEnabled' => true,

        /**
         * Enable remote file access for external images
         */
        'isRemoteEnabled' => true,

        /**
         * Use HTML5 parser
         */
        'isHtml5ParserEnabled' => true,

        /**
         * Paper settings
         */
        'default_paper_size' => 'a4',
        'default_paper_orientation' => 'portrait',

        /**
         * Image processing settings - CRITICAL FOR FIXING IMAGEMAGICK ERROR
         */
        'dpi' => 96,
        'enable_gd' => true,
        'enable_imagick' => false,

        /**
         * Additional security and performance settings
         */
        'log_output_file' => storage_path('logs/dompdf.log'),
        'enable_css_float' => true,
        'enable_javascript' => false,

        /**
         * Image handling options
         */
        'enable_remote' => true,
        'font_height_ratio' => 1.1,
        'enable_html5_parser' => true,
    ],
];
