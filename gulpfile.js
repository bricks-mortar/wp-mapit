'use strict';

var path = require('path');
var gulp = require('gulp'),
  browserify = require('browserify'),
  source = require('vinyl-source-stream'),
  buffer = require('vinyl-buffer'),
  sourcemaps = require('gulp-sourcemaps'),
  minifycss = require('gulp-minify-css'),
  sass = require('gulp-sass'),
  uglify = require('gulp-uglify'),
  del = require('del'),
  babelify = require('babelify');


gulp.task('styles', function() {
  return gulp.src('./assets/css/*.scss')
    .pipe(sass({
      includePaths: require('node-bourbon').includePaths
    }))
    .pipe(minifycss())
    .pipe(gulp.dest('./assets/css'));
});


gulp.task('scripts', function() {
  browserify('./assets/js/frontend/index.js')
    .transform(babelify)
    .bundle()
    .pipe(source('wp-mapit.js'))
    .pipe(buffer())
    .pipe(sourcemaps.init({ loadMaps: true }))
    .pipe(uglify())
    .pipe(sourcemaps.write('./'))
    .pipe(gulp.dest('./assets/js'));
});

gulp.task('clean', function() {
  del([
    './assets/css/*.css',
    './assets/js/*.js'
  ]);
});

gulp.task('build', ['clean'], function() {
  gulp.start(['styles', 'scripts']);
});

gulp.task('watch', function() {
  gulp.watch('./assets/css/*.scss', ['styles']);
  gulp.watch('./assets/js/frontend/index.js', ['scripts']);
});
