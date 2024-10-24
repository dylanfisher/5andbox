import esbuild from 'esbuild';
import { sassPlugin } from 'esbuild-sass-plugin';
import postcss from 'postcss';
import autoprefixer from 'autoprefixer';
import notifier from 'node-notifier';

const buildOptions = {
  entryPoints: [
    'assets/stylesheets/style.scss',
    'assets/javascripts/src/app.js'
  ],
  bundle: true,
  minify: true,
  format: 'iife',
  platform: 'browser',
  outdir: 'assets/dist',
  external: ['*.woff', '*.woff2'],
  logLevel: 'error'
};

const rebuildPlugin = {
  name: 'rebuild',
  setup(build) {
    build.onEnd(result => {
      if ( !result || result.errors.length ) {
        console.error('watch build error:', result);
        notifier.notify({
          title: 'esbuild error',
          message: result.pluginName
        });
      } else {
        console.log(`Built at ${new Date().toLocaleTimeString()}`);
      }
    });
  },
};

const sassPostcssPlugin = sassPlugin({
  quietDeps: true,
  async transform(source, resolveDir) {
    const { css } = await postcss([autoprefixer]).process(source, { from: undefined });
    return css;
  },
});

const ctx = await esbuild.context({ ...buildOptions, plugins: [rebuildPlugin, sassPostcssPlugin]  });
await ctx.watch();
