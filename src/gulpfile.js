var gulp = require('gulp'),
	compass = require('gulp-compass'),
	jshint = require('gulp-jshint'),
	stylish = require('jshint-stylish'),
	imagemin = require('gulp-imagemin'),
	uglify = require('gulp-uglify'),
	util = require('gulp-util'),
	concat = require('gulp-concat');

var PUBLIC_DIR = 'public/',
	RESOURCES_DIR = 'resources/',
	ASSETS_DIR = RESOURCES_DIR + 'assets/',
	SASS_DIR = ASSETS_DIR + 'sass/',
	CSS_DIR = PUBLIC_DIR + 'css/',
	JS_DIR = PUBLIC_DIR + 'js/',
	IMAGES_DIR = PUBLIC_DIR + 'images/';

gulp.task('compass', function()
{
	gulp.src(SASS_DIR + '**/*.scss')
		.pipe(compass(
		{
			config_file: 'config.rb',
			css: CSS_DIR,
			sass: SASS_DIR
		}))
		.on('error', function(error)
		{
			console.log(error);

			this.emit('end');
		})
		.pipe(gulp.dest(CSS_DIR));
});

gulp.task('lint', function()
{
	return gulp.src(ASSETS_DIR + 'js/**/*.js')
		.pipe(jshint())
		.pipe(jshint.reporter(stylish));
});

gulp.task('uglify', function()
{
	gulp.src(ASSETS_DIR + 'js/**/*.js')
		//.pipe(uglify().on('error', util.log))
		.pipe(gulp.dest(JS_DIR));
});

gulp.task('imagemin', function()
{
	return gulp.src(ASSETS_DIR + 'images/*')
		.pipe(imagemin(
			{
				progressive: true,
				svgoPlugins: [{ removeViewBox: false }]
			}))
		.pipe(gulp.dest(IMAGES_DIR));
});

gulp.task('watch', function()
{
	gulp.watch(SASS_DIR + '**/*.scss', ['compass']);
	gulp.watch(ASSETS_DIR + 'js/**/*.js', ['lint', 'uglify']);
});

gulp.task('default', ['compass', 'lint', 'uglify', 'imagemin', 'watch']);