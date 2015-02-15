// Based on Chris Coyier's Grunt boilerplate
// https://github.com/chriscoyier/My-Grunt-Boilerplate

module.exports = function(grunt) {

  require('time-grunt')(grunt);

  grunt.initConfig({

  pkg: grunt.file.readJSON('package.json'),

  sass: {
    dist: {
    options: {
      // cssmin will minify later
      style: 'expanded'
    },
    files: {
      'css/build/style.css': 'css/sass/style.scss'
    }
    }
  },

  autoprefixer: {
    options: {
    browsers: ['last 2 version']
    },
    multiple_files: {
    expand: true,
    flatten: true,
    src: 'css/build/*.css',
    dest: 'css/build/prefixed/'
    }
  },

  cssmin: {
    combine: {
    files: {
      'style.css': ['css/build/prefixed/style.css']
    }
    }
  },

  jshint: {
    beforeconcat: ['js/application/*.js']
  },

  concat: {
    dist: {
    src: [
      'js/application/vendor/*.js',
      'js/application/*.js'
    ],
    dest: 'js/build/application.js'
    }
  },

  uglify: {
    build: {
    src: 'js/build/application.js',
    dest: 'js/build/application.min.js'
    }
  },

  imagemin: {
    dynamic: {
    files: [{
      expand: true,
      cwd: 'images/',
      src: ['**/*.{png,jpg,gif}'],
      dest: 'images/'
    }]
    }
  },

  watch: {
    options: {
    livereload: true,
    },
    html: {
    files: ['*.html'],
    options: {
      spawn: false,
    }
    },
    php: {
    files: ['**/*.php'],
    options: {
      spawn: false,
    }
    },
    scripts: {
    files: ['js/application/**/*.js'],
    tasks: ['jshint', 'concat', 'uglify'],
    options: {
      spawn: false,
    }
    },
    css: {
    files: ['css/sass/**/*.scss'],
    tasks: ['sass', 'autoprefixer', 'cssmin'],
    options: {
      spawn: false,
    }
    },
    images: {
    files: ['images/**/*.{png,jpg,gif}', 'images/*.{png,jpg,gif}'],
    tasks: ['imagemin'],
    options: {
      spawn: false,
    }
    }
  },

  connect: {
    server: {
    options: {
      port: 8000,
      base: './'
    }
    }
  },

  });

  require('load-grunt-tasks')(grunt);

  // Default Task is basically a rebuild
  grunt.registerTask('default', ['concat', 'uglify', 'sass', 'imagemin']);

  grunt.registerTask('dev', ['connect', 'watch']);

};
