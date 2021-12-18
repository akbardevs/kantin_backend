const mix = require("laravel-mix");
const path = require("path");
const tailwindcss = require("tailwindcss");
// const cssImport = require("postcss-import"); // ?? do we need this?
/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel applications. By default, we are compiling the CSS
 | file for the application as well as bundling up all the JS files.
 |
 */

mix
  .js("resources/js/app.js", "public/js")
  .postCss("resources/css/app.css", "public/css", [
    tailwindcss("tailwind.config.js")
  ])
  .webpackConfig({
    
    output: { chunkFilename: "js/[name].js?id=[chunkhash]" },
    resolve: {
      extensions: [".js", ".svelte"],
      mainFields: ["svelte", "browser", "module", "main"],
      alias: {
        "@": path.resolve("resources/js")
      }
    },
    
    module: {
      rules: [
        {
          test: /\.(svelte)$/,
          use: {
            loader: "svelte-loader",
            options: {
              emitCss: true,
              hotReload: true,
              emitCss: true,
              customElement: true
            }
          }
        }
      ]
    }
  })
  .version()
  // .sourceMaps()
  // .browserSync('127.0.0.1:8000')
  ;
