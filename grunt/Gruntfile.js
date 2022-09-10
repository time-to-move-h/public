module.exports = function(grunt) {



	grunt.initConfig({
		
		uglify: {
			dev: {
				options: {
					mangle: true
				},
				files: [{
					expand: true,
					cwd: "../app/controller",
					src: ["*.js", "!*.min.js"],
					dest: "../../dist/ctrl",
					ext: ".min.js",
					rename: function (dst, src) {
						// To keep src js files and make new files as *.min.js :
						// return dst + '/' + src.replace('.js', '.min.js');
						// Or to override to src :
						return src;
					}
				}]
			}
		}


	  });


	

	// grunt.initConfig({
	// 	uglify: {
	// 	  my_target: {
	// 		files: { '../dist/ctrl/calendar.js': '../app/controller/calendar.js' }
	// 	  }
	// 	}
	//   });



	// Project configuration.
//   grunt.initConfig({
// 	uglify: {
// 	  options: {
// 		mangle: false
// 	  },
// 	  my_target: {
// 		files: {			
// 		  '../dist/ctrl/wrwerw.js': ['../app/controller/calendar.js']
// 		}
// 	  }
// 	}
//   });
  

  // Load the plugin that provides the "uglify" task.
  grunt.loadNpmTasks('grunt-contrib-uglify');

  // Default task(s).
  grunt.registerTask('default', ['uglify']);

};