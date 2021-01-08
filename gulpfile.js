const del = require( 'del' ),
	  gulp = require( 'gulp' ),
	  csso = require( 'gulp-csso' ),
	  sass = require( 'gulp-sass' ),
	  terser = require( 'gulp-terser' );

gulp.task( 'styles', () => {
	return gulp.src( 'assets/sass/**/*.scss' )
		.pipe( sass().on( 'error', sass.logError ) )
		.pipe( csso() )
		.pipe( gulp.dest( 'dist/css/' ) );
} );

gulp.task( 'clean', () => {
	return del( [
		'dist/css/style.css',
	] );
} );

gulp.task( 'build', gulp.series( [ 'clean', 'styles' ] ) );

gulp.task( 'watch', () => {
	gulp.watch( 'assets/sass/**/*.scss', done => {
		gulp.series( [ 'clean', 'styles' ] )( done );
	} );
} );
