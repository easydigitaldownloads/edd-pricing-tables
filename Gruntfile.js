module.exports = function(grunt) {

  grunt.registerTask('watch', [ 'watch' ]);

  grunt.initConfig({
    pkg: grunt.file.readJSON('package.json'),

    // LESS CSS
    less: {
      style: {
        options: {
          compress: true
        },
        files: {
          "assets/css/edd-pricing-tables.css": "assets/less/edd-pricing-tables.less"
        }
      }
    },

    // watch our project for changes
    watch: {

      // CSS
      css: {
        files: ['assets/less/**/*.less'],
        tasks: ['less:style'],
      },

    }
  });

  // Saves having to declare each dependency
  require( "matchdep" ).filterDev( "grunt-*" ).forEach( grunt.loadNpmTasks );

  grunt.registerTask('default', ['less' ]);
};
