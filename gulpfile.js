'use strict';

var gulp           = require( 'gulp' );
var styles         = require( 'gulp-sass' );
var html           = require( 'gulp-pug' );
var plumber        = require( 'gulp-plumber' );
var autoprefixer   = require( 'gulp-autoprefixer' );
var images         = require( 'gulp-imagemin' );
var sourcemaps     = require( 'gulp-sourcemaps' );
var minscripts     = require( 'gulp-uglify' );       // минифицирует js
var minstyles      = require( 'gulp-clean-css' );
var rename         = require( 'gulp-rename' );       // добавляет суфикс min к имени файла
var cache          = require( 'gulp-cache' );
var del            = require( 'del' );
var browserSync    = require( 'browser-sync' );
var zip            = require( 'gulp-zip' );
var concat         = require( 'gulp-concat' );



styles.compiler      = require( 'node-sass' );



gulp.task( 'public_scripts', function() {
	return gulp.src( './src/scripts/public/*.js' )
		.pipe( plumber() )
		.pipe( concat( 'public.js') )
		.pipe( gulp.dest( './scripts/' ) );
	}
);


gulp.task( 'video', function () {
	return gulp.src( './src/video/*.*' )
		.pipe( gulp.dest( './video/' ) );
} );


gulp.task( 'fonts', function () {
	return gulp.src( './src/fonts/*.*' )
		.pipe( gulp.dest( './fonts/' ) );
} );

 
gulp.task( 'styles', function () {
	return gulp.src( [ './src/styles/*.scss' ] )
		.pipe( plumber() )
		.pipe( sourcemaps.init() )
		.pipe( styles().on( 'error', styles.logError ) )
		.pipe( autoprefixer( [ 'last 15 versions', '> 1%', 'ie 11' ], { cascade: true } ) )
		.pipe( sourcemaps.write( '.' ) )
		.pipe( gulp.dest( './styles/' ) )
		.on( 'end', browserSync.reload );
} );


gulp.task( 'minstyles', function () {
	return gulp.src( [ './styles/*.css', '!./styles/*.min.css' ] )
		.pipe( minstyles( { compatibility: 'ie8' } ) )
		.pipe( rename( { suffix: '.min' } ) )
		.pipe( gulp.dest( './styles/' ) );
} );
 


gulp.task( 'html', function () {
	return gulp.src( [ './src/views/*.pug', '!./src/views/index.pug' ] )
		.pipe( plumber() )
		.pipe( html( { pretty: true } ) )
		.pipe( gulp.dest( './examples/' ) )
		.on( 'end', browserSync.reload );
} );



gulp.task( 'index', function () {
	return gulp.src( './src/views/index.pug' )
		.pipe( plumber() )
		.pipe( html( { pretty: true } ) )
		.pipe( gulp.dest( './' ) )
		.on( 'end', browserSync.reload );
} );



gulp.task( 'other_scripts', function () {
	return gulp.src( [ './src/scripts/*.js' ] )
		.pipe( plumber() )
		.pipe( gulp.dest( './scripts/' ) )
		// .pipe( minscripts() )
		.pipe( rename( { suffix: '.min' } ) )
		.pipe( gulp.dest( './scripts/' ) )
		.on( 'end', browserSync.reload );
} );



gulp.task( 'minscripts', function () {
	return gulp.src( [ './scripts/*.js', '!./scripts/*.min.js' ] )
		.pipe( plumber() )
		.pipe( minscripts() )
		.pipe( rename( { suffix: '.min' } ) )
		.pipe( gulp.dest( './scripts/' ) );
} );


gulp.task( 'userfiles', function () {
	return gulp.src( './src/userfiles/**/*.{png,jpg,svg,gif,svg}' )
		.pipe( plumber() )
		.pipe( cache( images() ) )
		.pipe( gulp.dest( './examples/userfiles/' ) )
		.on( 'end', browserSync.reload );
	}
);


gulp.task( 'images', function () {
	return gulp.src( './src/images/**/*.{png,jpg,svg,gif,svg}' )
		.pipe( plumber() )
		.pipe( cache( images() ) )
		.pipe( gulp.dest( './images/' ) )
		.on( 'end', browserSync.reload );
	}
);


gulp.task( 'server', function () {
	browserSync.init({
		server: {
			baseDir: './'
		}
	} );
} );


gulp.task( 'removebuild', function () {
	return del.sync( '/examples/' );
} );


gulp.task( 'clearcache', function () {
	return cache.clearAll();
} );


gulp.task( 'minify', gulp.series( 'minstyles', 'minscripts' ) );


gulp.task( 'scripts', gulp.series( 'public_scripts', 'other_scripts', 'minscripts' ) );



gulp.task( 'watch', function () {
	gulp.watch( './src/styles/**/*.scss',                 gulp.series( 'styles') );
	gulp.watch( './src/views/**/*.pug',                   gulp.series( 'html', 'index' ) );
	gulp.watch( './src/scripts/**/*.js',                  gulp.series( 'scripts' ) );
	gulp.watch( './src/images/**/*.{png,jpg,svg,gif}',    gulp.series( 'images' ) );
	gulp.watch( './src/userfiles/**/*.{png,jpg,svg,gif}', gulp.series( 'userfiles' ) );
	gulp.watch( './src/video/**/*.*',                     gulp.series( 'video' ) );
	gulp.watch( './src/fonts/**/*.*',                     gulp.series( 'fonts' ) );
} );



gulp.task( 'default', gulp.series(
	gulp.parallel( 'html', 'index', 'styles', 'scripts', 'images', 'userfiles', 'video', 'fonts', 'public_scripts' ),
	gulp.parallel( 'watch', 'server' )
) );