module.exports = function(grunt) {

  // ftp host
  var FTP_HOST = 'ftp.mkv25.net';
  var FTP_USER_LIVE = 'mkv25-live'

  // files to upload and exclude
  var FTP_LOCAL_FOLDER = "../../web";
  var FTP_DEST_FOLDER = "";
  var FTP_EXCLUSIONS_COMMON = ['.ftp*', '.git*', '.hta*', 'deploy', '*.fdproj', 'tasklist.md', 'readme.md', '.sublime', 'composer', 'composer.lock', '.DS_Store'];

  // auth details for live
  var FTP_LIVE_AUTH = {
    host: FTP_HOST,
    port: 21,
    authKey: FTP_USER_LIVE
  };

  // load plugins
  grunt.loadNpmTasks('grunt-ftp-deploy');
  grunt.loadNpmTasks('grunt-debug-task');

  // Project configuration.
  grunt.initConfig({
    pkg: grunt.file.readJSON('package.json'),

    "ftp-deploy": {
      "live": {
        auth: FTP_LIVE_AUTH,
        src: FTP_LOCAL_FOLDER,
        dest: FTP_DEST_FOLDER,
        exclusions: FTP_EXCLUSIONS_COMMON,
        forceVerbose: true
      }
    }
  });

  // Default task(s).
  grunt.registerTask('release', ['ftp-deploy:live']);
}
