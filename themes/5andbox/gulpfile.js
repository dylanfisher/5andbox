// Include gulp
var gulp = require('gulp');

// Include Plugins
var jshint     = require('gulp-jshint');
var sass       = require('gulp-sass');
// TODO: Add autoprefixer
var concat     = require('gulp-concat');
var uglify     = require('gulp-uglify');
var rename     = require('gulp-rename');
var livereload = require('gulp-livereload');
var notify     = require('gulp-notify');

// Lint Task
gulp.task('lint', function() {
  return gulp.src([
      'js/src/scripts/init.js',
      'js/src/scripts/**/*.js'
    ])
    .pipe(jshint())
    // Use gulp-notify as jshint reporter
    .pipe(notify(function(file) {
      if (file.jshint.success) {
        // Don't show something if success
        return false;
      }

      var errors = file.jshint.results.map(function(data) {
        if (data.error) {
          return "(" + data.error.line + ':' + data.error.character + ') ' + data.error.reason;
        }
      }).join("\n");
      return file.relative + " (" + file.jshint.results.length + " errors)\n" + errors;
    }))
    .on('error', onError);
});

// Compile Sass
gulp.task('sass', function() {
  return gulp.src('css/style.scss')
    .pipe(sass({outputStyle: 'compressed'})).on('error', sass.logError)
    .pipe(rename('style.css'))
    .pipe(gulp.dest('./'))
    .pipe(livereload())
    .on('error', onError);
});

// Concatenate & Minify JS
gulp.task('scripts', function() {
  return gulp.src([
      'js/src/vendor/*.js',
      'js/src/vendor/**/*.js',
      'js/src/init.js',
      'js/src/scripts/*.js',
      'js/src/scripts/**/*.js',
    ])
    .pipe(concat('application.js'))
    .pipe(gulp.dest('js/dist'))
    .pipe(rename('application.min.js'))
    .pipe(uglify())
    .pipe(gulp.dest('js/dist'))
    .pipe(livereload())
    .on('error', onError);
});

// Watch Files for Changes
gulp.task('watch', function() {
  livereload.listen();
  gulp.watch('**/*.php', livereload.reload);
  gulp.watch('js/src/**/*.js', ['lint', 'scripts']);
  gulp.watch('css/**/*.scss', ['sass']);;
});

// Default Task
gulp.task('default', ['lint', 'sass', 'scripts', 'watch']);

// Catch errors
function onError(err) {
  console.log(err);
  this.emit('end');
}
