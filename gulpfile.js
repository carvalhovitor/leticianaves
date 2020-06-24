let gulp = require('gulp');   
let rename = require('gulp-rename');
let uglify = require('gulp-uglify-es').default;
let babel = require('gulp-babel');
let sass = require('gulp-sass');
let uglifycss = require('gulp-uglifycss');

sass.compiler = require('node-sass');

const assets = 'assets/';
 
gulp.task('js', function() {
    return gulp.src('assets/js/index.js')
        .pipe(rename('index.min.js'))
        .pipe(babel({
            presets: ['@babel/env']
        }))
        .pipe(uglify())
        .pipe(gulp.dest('assets/js/'));
});

gulp.task('css', function() {
    return gulp.src(assets + 'scss/index.scss')
        .pipe(sass().on('error', sass.logError))
        .pipe(uglifycss())
        .pipe(gulp.dest(assets + 'css'));
})

gulp.task('default', function() {
    gulp.watch('assets/js/index.js', gulp.series('js'));
    gulp.watch(assets + 'scss/*.scss', gulp.series('css'));
});