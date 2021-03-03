const gulp = require('gulp');
const sass = require('gulp-sass');
const jade = require('gulp-jade');
const browserSync  = require('browser-sync').create();
const del = require('del');

const paths = {
	styles: {
		src: './src/scss/**/*[^_].scss',
		dest: './css/'
	},
	jade: {
		src: './src/jade/**/[^_]*.jade',
		src_watch: './src/jade/**/*.jade',
		dest: './'
	}
};

function clean() {
	return del(['./css/**']);
}

function sync(){
	browserSync.init({
        server: {
			baseDir: "./"
		},
		host: "localhost"
    });
}

function styles() {
	return gulp.src(paths.styles.src)
		.pipe(sass())
		.pipe(gulp.dest(paths.styles.dest))
        .pipe(browserSync.stream());
}

function jade_func() {
	var YOUR_LOCALS = {};

	return gulp.src(paths.jade.src)
		.pipe(jade({
			locals: YOUR_LOCALS,
			pretty: '\t'
		}))
		.pipe(gulp.dest(paths.jade.dest));
}

function watch(){
	//gulp.watch(paths.jade.src_watch, jade_func);
	gulp.watch(paths.styles.src, styles);
	//gulp.watch("./**/*.js").on('change', browserSync.reload);
	//gulp.watch("./**/*.html").on('change', browserSync.reload);
}

function serve(){
	//sync();
	watch();
}

exports.clean = clean;
exports.styles = styles;
exports.jade = jade;
exports.watch = watch;
exports.sync = sync;

var build = gulp.series(clean, gulp.parallel(styles, jade_func));

gulp.task('build', build);
gulp.task('default', serve);
