module.exports = function (grunt) {

    require('load-grunt-tasks')(grunt);

    grunt.initConfig({
        jshint: {
            all: ['js/create_hike.js', 'js/map.js']
        },

        uglify: {
            options: {
                mangle: false
            },
            my_target: {
                files: {
                    'js/map.min.js': 'js/map.js',
                    'js/create_hike.min.js': 'js/create_hike.js'
                }
            }
        },

        concat: {
            dist: {
                src: ['bower_components/jquery/dist/jquery.min.js', 'js/other.js', 'bower_components/angular/angular.min.js', 'bower_components/angular-animate/angular-animate.min.js', 'js/map.min.js', 'js/create_hike.min.js', 'bower_components/jqueryui/jquery-ui.min.js'],
                dest: 'js/app.min.js'
            }
        },

        compass: {
            dist: {
                options: {
                    sassDir: 'sass',
                    cssDir: 'css',
                    environment: 'production'
                }
            }
        },
        cssmin: {
            combine: {
                files: {
                    'css/style.min.css': ['css/style.css', 'bower_components/leaflet/dist/leaflet.css', 'bower_components/jqueryui/themes/base/jquery-ui.css']
                }
            }
        },

        watch: {
            js: {
                files: ['js/*.js', '!js/min.*'],
                tasks: ['jshint', 'uglify', 'concat'],
                options: {
                    spawn: false
                }
            },
            css: {
                files: ['css/*.css', 'sass/*.scss', '!css/min.*'],
                tasks: ['compass', 'cssmin'],
                options: {
                    spawn: false
                }
            }
        }


    });

    grunt.registerTask('default', ['jshint', 'uglify', 'concat', 'compass', 'cssmin']);

}