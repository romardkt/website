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
            '../../../node_modules/select2/select2.css',
            '../../../node_modules/select2/select2-bootstrap.css',
        ], 'public/css/cupa.min.css')
        .babel([
            '../../../node_modules/jquery/dist/jquery.min.js',
            '../../../node_modules/bootstrap-sass/assets/javascripts/bootstrap.min.js',
            '../../../node_modules/select2/select2.js',
            'global.js'
        ], 'public/js/cupa.min.js')
        .copy('node_modules/font-awesome/fonts', 'public/build/fonts')
        .copy('node_modules/select2/select2.png', 'public/build/css')
        .version([
            'css/cupa.min.css',
            'js/cupa.min.js'
        ]);
});
