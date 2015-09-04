var elixir = require('laravel-elixir');

/*
 |--------------------------------------------------------------------------
 | Elixir Asset Management
 |--------------------------------------------------------------------------
 |
 | Elixir provides a clean, fluent API for defining some basic Gulp tasks
 | for your Laravel application. By default, we are compiling the Sass
 | file for our application, as well as publishing vendor resources.
 |
 */

elixir(function(mix) {
    mix.sass([
            'app.scss',
            '../../../bower_components/select2/select2.css',
            //'../../../bower_components/select2/select2-bootstrap.css',
            '../../../bower_components/select2-bootstrap-css/select2-bootstrap.min.css',
            '../../../bower_components/pickadate/lib/themes/default.css',
            '../../../bower_components/pickadate/lib/themes/default.date.css',
            '../../../bower_components/clockpicker/dist/bootstrap-clockpicker.min.css',
        ], 'public/css/cupa.min.css')
        .scripts([
            '../../../bower_components/jquery/dist/jquery.min.js',
            '../../../bower_components/bootstrap-sass/assets/javascripts/bootstrap.min.js',
            '../../../bower_components/select2/select2.js',
            '../../../bower_components/pickadate/lib/compressed/picker.js',
            '../../../bower_components/pickadate/lib/compressed/picker.date.js',
            '../../../bower_components/select2/select2.js',
            '../../../bower_components/clockpicker/dist/bootstrap-clockpicker.min.js',
            'global.js'
        ], 'public/js/cupa.min.js')
        .copy('bower_components/font-awesome/fonts', 'public/build/fonts')
        .copy('bower_components/select2/select2.png', 'public/build/css')
        .copy('bower_components/select2/select2-spinner.gif', 'public/build/css')
        .copy('bower_components/ckeditor', 'public/ckeditor')
        .version([
            'css/cupa.min.css',
            'js/cupa.min.js'
        ]);
});
