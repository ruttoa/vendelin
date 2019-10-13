<?php
/**
 * Forked from Clean Image Filenames -plugin https://wordpress.org/plugins/clean-image-filenames/
 */

/**
 * This function runs when files are being uploaded to the WordPress media 
 * library. 
 * 
 * Stores original filename to a transient to be used later in a file title.
 *
 * @param array The file information including the filename in $file['name'].
 * @return array The file information with the cleaned or original filename.
 */

if ( ! function_exists( 'theme_upload_filter' ) ) :
    function theme_upload_filter( $file ) {
        
        $original_filename = pathinfo( $file[ 'name' ] );
        set_transient( '_clean_image_filenames_original_filename', $original_filename[ 'filename' ], 60 );
        
        $file = theme_clean_filename( $file );

        return $file;
    }
    add_action( 'wp_handle_upload_prefilter', 'theme_upload_filter' );
endif; 

/**
 * Performs the filename cleaning.
 *
 * This function performs the actual cleaning of the filename. It takes an 
 * array with the file information, cleans the filename and sends the file 
 * information back to where the function was called from. 
 *
 * @param array File details including the filename in $file['name'].
 * @return array The $file array with cleaned filename.
 */
if ( ! function_exists( 'theme_clean_filename' ) ) :
    function theme_clean_filename( $file ) {

        $input = array(
            '"',
            '%22', //double quote
            'đ',
            'ŧ',
            'ø',
            'ŋ'
        );
        $output = array(
            '',
            '',
            'd',
            't',
            'o',
            'n'
        );
        $path = pathinfo( $file[ 'name' ] );
        $new_filename = str_replace( $input, $output, $path[ 'filename' ] ); // Checks for special characters that iconv can't handle
        $new_filename = iconv( 'UTF-8','ASCII//TRANSLIT//IGNORE', $new_filename );
        $file[ 'name' ] = sanitize_title( $new_filename ) . '.' . $path[ 'extension' ];

        return $file;
    }
endif; 

/**
 * Set attachment title to original, un-cleaned filename
 *
 * The original, un-cleaned filename is saved as a transient called 
 * _clean_image_filenames_original_filename just before the filename is cleaned 
 * and saved. When WordPress adds the attachment to the database, this function 
 * picks up the original filename from the transient and saves it as the 
 * attachment title.
 *
 * @param int Attachment post ID.
 */	
if ( ! function_exists( 'theme_update_attachment_title' ) ) :
    function theme_update_attachment_title( $attachment_id ) {
        
        $original_filename = get_transient( '_clean_image_filenames_original_filename' );
        
        if ( $original_filename ) {
            wp_update_post( array( 'ID' => $attachment_id, 'post_title' => $original_filename ) );
            delete_transient( '_clean_image_filenames_original_filename' );
        }
    }
    add_action( 'add_attachment', 'theme_update_attachment_title' );
endif; 
