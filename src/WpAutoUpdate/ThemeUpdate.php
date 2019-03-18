<?php
namespace SashokNekulin\WpAutoUpdate;

class ThemeUpdate{
    protected $file;
    protected $theme;
    protected $basename;
    protected $version;

    private $username;
    private $repository;
    private $authorize_token;
    private $github_response;
    
    public function __construct( $file ) {
        $this->file = $file;
        $this->basename = wp_basename( $this->file );
        $this->theme  = wp_get_theme( $this->basename );
        $this->version = $this->theme->get( 'Version' );
        return $this;
    }
    public function set_username( $username ) {
        $this->username = $username;
    }
    public function set_repository( $repository ) {
        $this->repository = $repository;
    }
    public function authorize( $token ) {
        $this->authorize_token = $token;
    }
    private function get_repository_info() {
        if ( is_null( $this->github_response ) ) { // Do we have a response?
          $request_uri = sprintf( 'https://api.github.com/repos/%s/%s/releases', $this->username, $this->repository ); // Build URI
          if( $this->authorize_token ) { // Is there an access token?
              $request_uri = add_query_arg( 'access_token', $this->authorize_token, $request_uri ); // Append it
          }        
          $response = json_decode( wp_remote_retrieve_body( wp_remote_get( $request_uri ) ), true ); // Get JSON and parse it
          if( is_array( $response ) ) { // If it is an array
              $response = current( $response ); // Get the first item
          }
          if( $this->authorize_token ) { // Is there an access token?
              $response['zipball_url'] = add_query_arg( 'access_token', $this->authorize_token, $response['zipball_url'] ); // Update our zip url with token
          }
          $this->github_response = $response; // Set it to our property  
        }
    }
    public function modify_transient( $transient ) {
        if( property_exists( $transient, 'checked') ) { // Check if transient has a checked property
            if( $checked = $transient->checked ) { // Did Wordpress check for updates?
                $this->get_repository_info(); // Get the repo info
                $out_of_date = version_compare( $this->github_response['tag_name'], $this->version, 'gt' ); // Check if we're out of date
                if( $out_of_date ) {
                    $new_files = $this->github_response['zipball_url']; // Get the ZIP
                    $slug = current( explode('/', $this->basename ) ); // Create valid slug
                    $theme = array( // setup our plugin info
                        'url' => $this->theme->get("ThemeURI"),
                        'slug' => $slug,
                        'package' => $new_files,
                        'new_version' => $this->github_response['tag_name']
                    );
                    //$transient->response[$this->basename] = (object) $theme; // Return it in response
                    $transient->response[$this->basename] =  $theme;
                }
            }
        }
        return $transient; // Return filtered transient
    }
    public function after_install( $r, $result ) {
        $install_directory = get_template_directory(); 
        $a = rename( $install_directory, get_theme_root() . '/' . $this->basename);
        $current_theme = get_option('current_theme');
        if( $current_theme == $this->theme->get( 'Name' ) ){
            update_option( 'template', $this->basename, true );
            update_option( 'stylesheet', $this->basename, true );
        }
    }
    public function initialize() {
        add_filter( 'pre_set_site_transient_update_themes', array( $this, 'modify_transient' ), 10, 1 );
        add_filter( 'upgrader_process_complete', array( $this, 'after_install' ), 10, 3 );
    }
}

?>