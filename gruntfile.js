module.exports = function(grunt) {

    // 1. All configuration goes here 
    grunt.initConfig({
        pkg: grunt.file.readJSON('package.json'),

        concat: {
            dist: {
                src: [
                    'js/qr_main.js',
                    'js/qr_adresse.js',
                    'js/qr_assist.js',
                    'js/qr_contact.js',
                    'js/qr_edit.js',
                    'js/qr_image.js',
                    'js/qr_info.js',
                    'js/qr_login.js',
                    'js/qr_new.js',
                    'js/qr_search.js',
                    'js/qr_show.js'
                ],
                dest: 'js/build/qrv.js',
                }
        },
        uglify: {
            build: {
                src: 'js/build/qrv.js',
                dest: 'js/build/qrv.min.js'
            }
        }

    });

    // 3. Where we tell Grunt we plan to use this plug-in.
    grunt.loadNpmTasks('grunt-contrib-concat');
    grunt.loadNpmTasks('grunt-contrib-uglify');

    // 4. Where we tell Grunt what to do when we type "grunt" into the terminal.
    grunt.registerTask('default', ['concat', 'uglify']);
};

