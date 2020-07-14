// const webpack = require('webpack');
const mix = require('laravel-mix');
const SKIN_PATH = './';

mix.options({
    processCssUrls: false
});

// mix.autoload({ 'jquery': ['window.$', 'window.jQuery'] })

// mix.autoload({
//     'jquery': ['$', 'window.$', 'window.jQuery', 'jQuery'],
//     'popper.js': ['window.Popper', 'Popper'],
// });

mix.webpackConfig(function(webpack) {
        return {
            resolve: {
                extensions: ['.js'],
                alias: {'@': __dirname + '/assets/js'}
            },

            // plugins: [
            //     new webpack.ProvidePlugin({
            //         'window.$': 'jquery',
            //         '$': 'jquery',
            //         'jQuery': 'jquery',
            //         'window.jQuery': 'jquery',
            //         'Popper': ['popper.js', 'default'],
            //         // Alert           : 'exports-loader?Alert!bootstrap/js/dist/alert',
            //         // Button          : 'exports-loader?Button!bootstrap/js/dist/button',
            //         // Carousel        : 'exports-loader?Carousel!bootstrap/js/dist/carousel',
            //         // Collapse        : 'exports-loader?Collapse!bootstrap/js/dist/collapse',
            //         // Dropdown        : 'exports-loader?Dropdown!bootstrap/js/dist/dropdown',
            //         // Modal           : 'exports-loader?Modal!bootstrap/js/dist/modal',
            //         // Popover         : 'exports-loader?Popover!bootstrap/js/dist/popover',
            //         // Scrollspy       : 'exports-loader?Scrollspy!bootstrap/js/dist/scrollspy',
            //         // Tab             : 'exports-loader?Tab!bootstrap/js/dist/tab',
            //         // Tooltip         : "exports-loader?Tooltip!bootstrap/js/dist/tooltip",
            //         // Util            : 'exports-loader?Util!bootstrap/js/dist/util',
            //     }),
            // ]
        }
    })
    .setPublicPath(`${SKIN_PATH}/public/`);

// Копирование директории иконочных шрифтов.
mix.copyDirectory('node_modules/font-awesome/fonts', SKIN_PATH + '/public/css/fonts/font-awesome');

// Копирование директории изображений.
mix.copyDirectory(SKIN_PATH + '/assets/images', SKIN_PATH + '/public/images');

// Компиляция ресурсов установщика.
mix.js(SKIN_PATH + '/assets/js/install.js', 'js')
    .sass(SKIN_PATH + '/assets/sass/install.scss', 'css');

// Компиляция ресурсов страницы входа.
mix.js(SKIN_PATH + '/assets/js/login.js', 'js')
    .sass(SKIN_PATH + '/assets/sass/login.scss', 'css');

// Компиляция основных ресурсов скина админ.панели.
mix.js(SKIN_PATH + '/assets/js/app.js', 'js')
    .sass(SKIN_PATH + '/assets/sass/app.scss', 'css');

// Компиляция ресурсов редактора кода.
mix.js(SKIN_PATH + '/assets/js/code-editor.js', 'js')
    .sass(SKIN_PATH + '/assets/sass/code-editor.scss', 'css')
    .copyDirectory(SKIN_PATH + '/assets/vendor/ngFileTree/images', SKIN_PATH + '/public/images/ngFileTree');

/**
 * This has the benefit of not needing to cache-bust the vendor.js file,
 * if no changes have been made to the dependencies that have been extracted.
 */
mix.extract([
    'bootstrap',
    'jquery',
    'jquery-datetimepicker',
    'jquery-mousewheel',
    'popper.js',
    // 'baguettebox.js',
    // 'codemirror',
    // '@emmetio/codemirror-plugin',
    // 'lodash',
]);

mix.inProduction() && mix.version();

/**
 * ----------------------------------------------------------------------------
 * Mix Asset Management
 * ----------------------------------------------------------------------------
 * Mix provides a clean, fluent API for defining some Webpack build steps
 * for your Laravel application. By default, we are compiling the Sass
 * file for the application as well as bundling up all the JS files.
 * ----------------------------------------------------------------------------
 * Full API Mix
 * ----------------------------------------------------------------------------
 */
// mix.js(src, output);
// mix.react(src, output); <-- Identical to mix.js(), but registers React Babel compilation.
// mix.preact(src, output); <-- Identical to mix.js(), but registers Preact compilation.
// mix.coffee(src, output); <-- Identical to mix.js(), but registers CoffeeScript compilation.
// mix.ts(src, output); <-- TypeScript support. Requires tsconfig.json to exist in the same folder as webpack.mix.js
// mix.extract(vendorLibs);
// mix.sass(src, output);
// mix.less(src, output);
// mix.stylus(src, output);
// mix.postCss(src, output, [require('postcss-some-plugin')()]);
// mix.browserSync('my-site.test');
// mix.combine(files, destination);
// mix.babel(files, destination); <-- Identical to mix.combine(), but also includes Babel compilation.
// mix.copy(from, to);
// mix.copyDirectory(fromDir, toDir);
// mix.minify(file);
// mix.sourceMaps(); // Enable sourcemaps
// mix.version(); // Enable versioning.
// mix.disableNotifications();
// mix.setPublicPath('path/to/public');
// mix.setResourceRoot('prefix/for/resource/locators');
// mix.autoload({}); <-- Will be passed to Webpack's ProvidePlugin.
// mix.webpackConfig({}); <-- Override webpack.config.js, without editing the file directly.
// mix.babelConfig({}); <-- Merge extra Babel configuration (plugins, etc.) with Mix's default.
// mix.then(function () {}) <-- Will be triggered each time Webpack finishes building.
// mix.override(function (webpackConfig) {}) <-- Will be triggered once the webpack config object has been fully generated by Mix.
// mix.dump(); <-- Dump the generated webpack config object to the console.
// mix.extend(name, handler) <-- Extend Mix's API with your own components.
// mix.options({
//   extractVueStyles: false, // Extract .vue component styling to file, rather than inline.
//   globalVueStyles: file, // Variables file to be imported in every component.
//   processCssUrls: true, // Process/optimize relative stylesheet url()'s. Set to false, if you don't want them touched.
//   purifyCss: false, // Remove unused CSS selectors.
//   terser: {}, // Terser-specific options. https://github.com/webpack-contrib/terser-webpack-plugin#options
//   postCss: [] // Post-CSS options: https://github.com/postcss/postcss/blob/master/docs/plugins.md
// });

// Aditional from https://scotch.io/tutorials/using-laravel-mix-with-webpack-for-all-your-assets
// .standaloneSass('src', output) // Faster, but isolated from Webpack.
// .fastSass('src', output) // Alias for mix.standaloneSass().
// .options({
//     extractVueStyles: false, // Extract .vue component styling to file, rather than inline.
//     uglify: {}, // Uglify-specific options. https://webpack.github.io/docs/list-of-plugins.html#uglifyjsplugin
// })
