// gulpfile.js
var gulp = require('gulp'),
    traceur = require('gulp-traceur')
    paths = {
       scripts: ['app/controller/modules/src/*.js', 'app/controller/modules/**/*.js']
    };
 
gulp.task('scripts', function () {
    gulp.src(paths.scripts)
        .pipe(traceur())
        .pipe(gulp.dest('dist/js/modules'));
});
 
gulp.task('watch', function() {
  gulp.watch(paths.scripts, ['scripts']);
});
 
gulp.task('default', ['scripts', 'watch']);