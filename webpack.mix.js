const mix = require('laravel-mix');

mix.js('resources/js/app.js', 'public/js')
   .vue()
   .sass('resources/sass/app.scss', 'public/css')
   .options({
       processCssUrls: false
   });

if (mix.inProduction()) {
    mix.version();
}
