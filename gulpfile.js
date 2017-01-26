const elixir = require('laravel-elixir');

// require('laravel-elixir-vue-2');

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

elixir(mix => {
  mix.sass([
      'app.scss',
      '../../../node_modules/font-awesome/css/font-awesome.css',
      '../../../node_modules/select2/select2.css',
      '../../../node_modules/select2-bootstrap-css/select2-bootstrap.css',
      '../../../node_modules/pickadate/lib/themes/default.css',
      '../../../node_modules/pickadate/lib/themes/default.date.css',
      '../../../node_modules/clockpicker/dist/bootstrap-clockpicker.css',
    ])
    .scripts([
      '../../../node_modules/jquery/dist/jquery.js',
      '../../../node_modules/bootstrap-sass/assets/javascripts/bootstrap.js',
      '../../../node_modules/select2/select2.js',
      '../../../node_modules/pickadate/lib/picker.js',
      '../../../node_modules/pickadate/lib/picker.date.js',
      '../../../node_modules/clockpicker/dist/bootstrap-clockpicker.min.js',
      '../../../node_modules/list.js/dist/list.js',
      'global.js',
    ], 'public/js/app.js')
    .copy('node_modules/ckeditor', 'public/ckeditor')
    .copy('node_modules/font-awesome/fonts', 'public/build/fonts')
    .copy('node_modules/select2/select2.png', 'public/build/css')
    .copy('node_modules/select2/select2-spinner.gif', 'public/build/css')
    .version(['css/app.css', 'js/app.js']);

  mix.phpUnit();
});
