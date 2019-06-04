// Include gulp
var gulp = require('gulp');

// Include Plugins
var sass         = require('gulp-sass');
var postcss      = require('gulp-postcss');
var gulp         = require('gulp');
var autoprefixer = require('autoprefixer');
var cssnano      = require('cssnano');
var concat       = require('gulp-concat');
var uglify       = require('gulp-uglify');
var rename       = require('gulp-rename');
var livereload   = require('gulp-livereload');

// Compile Sass
gulp.task('css', function() {
  return gulp.src('assets/stylesheets/style.scss')
    .pipe(sass({outputStyle: 'compressed'})).on('error', sass.logError)
    .pipe(rename('style.css'))
    .pipe(postcss([autoprefixer(), cssnano()]))
    .pipe(gulp.dest('./'))
    .pipe(livereload())
    .on('error', onError);
});

// Concatenate & Minify JS
gulp.task('scripts', function() {
  return gulp.src([
      'assets/javascripts/src/vendor/*.js',
      'assets/javascripts/src/vendor/**/*.js',
      'assets/javascripts/src/application.js',
      'assets/javascripts/src/scripts/*.js',
      'assets/javascripts/src/scripts/**/*.js',
    ])
    .pipe(concat('application.js'))
    .pipe(gulp.dest('assets/javascripts/dist'))
    .pipe(rename('application.min.js'))
    .pipe(uglify())
    .pipe(gulp.dest('assets/javascripts/dist'))
    .pipe(livereload())
    .on('error', onError);
});

// Watch Files for Changes
gulp.task('watch', function() {
  livereload.listen();
  gulp.watch('**/*.php').on('change', function(file) {
    livereload.changed(file);
  });
  gulp.watch('assets/javascripts/src/**/*.js', gulp.series('scripts'));
  gulp.watch('assets/stylesheets/**/*.scss', gulp.series('css'));
});

// Default Task
gulp.task('default', gulp.series(gulp.parallel('css', 'scripts'), 'watch'));

// Catch errors
function onError(err) {
  console.log(err);
  this.emit('end');
}
