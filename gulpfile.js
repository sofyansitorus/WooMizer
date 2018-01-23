var gulp = require('gulp');
var sass = require('gulp-sass');
var cleanCSS = require('gulp-clean-css');
var rename = require("gulp-rename");
var uglify = require('gulp-uglify');
var autoprefixer = require('gulp-autoprefixer');

var scssSrc = 'assets/scss/*.scss';
var scssDest = 'assets/css';

var cssSrc = 'assets/css/*.css';
var cssSrcExclude = '!assets/css/*.min.css';
var cssDest = 'assets/css';

var jsSrc = 'assets/js/*.js';
var jsSrcExclude = '!assets/js/*.min.js';
var jsDest = 'assets/js';

var AUTOPREFIXER_BROWSERS = [
    'Android 2.3',
    'Android >= 4',
    'Chrome >= 35',
    'Firefox >= 31',
    'Explorer >= 9',
    'iOS >= 7',
    'Opera >= 12',
    'Safari >= 7.1',
];

// Compiles SCSS files from /scss into /css
gulp.task('sass', function () {
    return gulp.src(scssSrc)
        .pipe(sass())
        .pipe(autoprefixer({
            browsers: AUTOPREFIXER_BROWSERS,
            cascade: false
        }))
        .pipe(gulp.dest(scssDest));
});

// Minify compiled CSS
gulp.task('minify-css', ['sass'], function () {
    return gulp.src([cssSrc, cssSrcExclude])
        .pipe(cleanCSS({
            compatibility: 'ie8'
        }))
        .pipe(rename({
            suffix: '.min'
        }))
        .pipe(gulp.dest(cssDest));
});

// Minify custom JS
gulp.task('minify-js', function () {
    return gulp.src([jsSrc, jsSrcExclude])
        .pipe(uglify())
        .pipe(rename({
            suffix: '.min'
        }))
        .pipe(gulp.dest(jsDest));
});

// Default task
gulp.task('default', ['sass', 'minify-css', 'minify-js']);

// Dev task with watch
gulp.task('watch', ['sass', 'minify-css', 'minify-js'], function () {
    gulp.watch(scssSrc, ['sass']);
    gulp.watch([cssSrc, cssSrcExclude], ['minify-css']);
    gulp.watch([jsSrc, jsSrcExclude], ['minify-js']);
});