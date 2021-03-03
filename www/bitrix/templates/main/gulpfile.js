var gulp        = require('gulp');
var minifyCss   = require('gulp-minify-css');
var sass        = require('gulp-sass');
var notify      = require('gulp-notify');
var browserSync = require('browser-sync');
var rename = require("gulp-rename");

const imagemin = require('gulp-imagemin');
const pngquant = require('imagemin-pngquant');

var reload      = browserSync.reload;

var paths = {
  html:['index.html'],
  css:['src/styles.scss.css']
};

gulp.task('mincss', function(){
  return gulp.src(paths.css)
    .pipe(sass().on('error', sass.logError))
    .pipe(minifyCss())
    .pipe(rename({basename: 'template_styles', suffix: '', extname: '.css'}))
    .pipe(gulp.dest(''))
    .pipe(reload({stream:true}));
});

gulp.task('minimages', function(){
	return gulp.src('src/images/*')
        .pipe(imagemin({
            progressive: true,
            svgoPlugins: [{removeViewBox: false}],
            use: [pngquant()]
        }))
        .pipe(gulp.dest('images'));
});

// ////////////////////////////////////////////////
// HTML 
// ///////////////////////////////////////////////
gulp.task('html', function(){
  gulp.src(paths.html)
  .pipe(reload({stream:true}));
});

// ////////////////////////////////////////////////
// Browser-Sync
// // /////////////////////////////////////////////
gulp.task('browserSync', function() {
  browserSync({
    server: {
      baseDir: "./"
    },
    port: 8080,
    open: true,
    notify: false
  });
});

gulp.task('watcher',function(){
  gulp.watch(paths.css, ['mincss']);
  gulp.watch(paths.html, ['html']);
});

gulp.task('default', ['watcher', 'browserSync']);