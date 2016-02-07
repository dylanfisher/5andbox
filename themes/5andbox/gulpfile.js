// Include gulp
var gulp = require('gulp');

// Include Plugins
var jshint     = require('gulp-jshint');
var sass       = require('gulp-sass');
var concat     = require('gulp-concat');
var uglify     = require('gulp-uglify');
var rename     = require('gulp-rename');
var livereload = require('gulp-livereload');

// Lint Task
gulp.task('lint', function() {
  return gulp.src('js/src/**/*.js')
    .pipe(jshint())
    .pipe(jshint.reporter('default'));
});

// Compile Sass
gulp.task('sass', function() {
  return gulp.src('scss/style.scss')
    .pipe(sass())
    .pipe(gulp.dest('./'))
    .pipe(livereload());
});

// Concatenate & Minify JS
gulp.task('scripts', function() {
  return gulp.src([
      'js/src/vendor/**/*.js',
      'js/src/init.js',
      'js/src/scripts/**/*.js',
    ])
    .pipe(concat('application.js'))
    .pipe(gulp.dest('js/dist'))
    .pipe(rename('application.min.js'))
    .pipe(uglify())
    .pipe(gulp.dest('js/dist'))
    .pipe(livereload());
});

// Watch Files for Changes
gulp.task('watch', function() {
  livereload.listen();
  gulp.watch('*.php', livereload.reload);
  gulp.watch('js/src/**/*.js', ['lint', 'scripts']);
  gulp.watch('scss/**/*.scss', ['sass']);
});

// Default Task
gulp.task('default', ['lint', 'sass', 'scripts', 'watch']);
