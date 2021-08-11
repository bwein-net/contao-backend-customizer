'use strict';

var gulp = require('gulp'),
    csso = require('gulp-csso'),
    ignore = require('gulp-ignore'),
    rename = require('gulp-rename'),
    svgo = require('gulp-svgo'),
    sass = require('gulp-sass')(require('sass')),
    uglify = require('gulp-uglify'),
    pump = require('pump');

gulp.task('minify-public-js', function (cb) {
    pump(
        [
            gulp.src('public/js/*.js'),
            ignore.exclude('*.min.js'),
            uglify(),
            rename({
                suffix: '.min'
            }),
            gulp.dest('public/js')
        ],
        cb
    );
});

gulp.task('minify-public-css', function (cb) {
    pump(
        [
            gulp.src('public/css/*.scss'),
            ignore.exclude('*.min.css'),
            sass(),
            csso({
                comments: false,
                restructure: false
            }),
            rename({
                suffix: '.min'
            }),
            gulp.dest('public/css')
        ],
        cb
    );
});

gulp.task('watch', function () {
    gulp.watch(
        [
            'public/js/*.js',
            '!public/js/*.min.js'
        ],
        gulp.series('minify-public-js')
    );

    gulp.watch(
        [
            'public/css/*.scss',
            '!public/css/*.min.css'
        ],
        gulp.series('minify-public-css')
    );
});

gulp.task('default', gulp.parallel('minify-public-js', 'minify-public-css'));
