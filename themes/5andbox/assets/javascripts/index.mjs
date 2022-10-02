import esbuild from 'esbuild';
import { sassPlugin } from 'esbuild-sass-plugin';
import postcss from 'postcss';
import autoprefixer from 'autoprefixer';

esbuild.build({
  entryPoints: [
    'assets/stylesheets/style.scss',
    'assets/javascripts/src/app.js'
  ],
  bundle: true,
  minify: true,
  format: 'iife',
  platform: 'browser',
  outdir: 'assets/dist',
  // outfile: 'assets/javascripts/dist/application.min.js',
  plugins: [
    sassPlugin({
      async transform(source) {
        // const { css } = await postcss([autoprefixer], {from: undefined}).process(source);
        const { css } = await postcss([autoprefixer]).process(source, { from: undefined })
        return css
      }
    })
  ],
  watch: {
    onRebuild(error, result) {
      if (error) {
        console.error('watch build failed:', error);
      } else {
        console.log('watch build succeeded:', result);
      }
    },
  },
}).then(result => {
  console.log('watching...');
}).catch((err) => {
  console.log(err);
});
