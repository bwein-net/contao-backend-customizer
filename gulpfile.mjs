'use strict';

import gulp from 'gulp';
import csso from 'gulp-csso';
import gulpSass from 'gulp-sass';
import sassEmbedded from 'sass-embedded';
const sass = gulpSass(sassEmbedded);
import ignore from 'gulp-ignore';
import rename from 'gulp-rename';
import uglify from 'gulp-uglify';
import pump from 'pump';

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
