<?php
/**
 * @author       Sweet Andi
 * @copyright  © 2026, Sweet Andi
 * @package      Kado\IO
 * @since        2026-03-23
 * @version      1.0.0
 */


declare( strict_types=1 );


namespace Kado\IO;


/**
 * This is a static helper class to handle MIME types.
 */
class MimeTypeTool
{


    #region // = = =   C O N S T R U C T O R   = = = = = = = = = = = = = = = = = = = = = = = = = = = =

    /**
     * Class is only statically accessible.
     */
    private function __construct() { }

    #endregion


    #region // - - -   P R I V A T E   S T A T I C   F I E L D S   - - - - - - - - - - - - - - - - - -

    private static array $mimeTypes = [
        'afl'     => 'video/animaflex',
        'ai'      => 'application/postscript',
        'aif'     => 'audio/x-aiff',
        'aifc'    => 'audio/x-aiff',
        'aiff'    => 'audio/x-aiff',
        'aim'     => 'application/x-aim',
        'arc'     => 'application/octet-stream',
        'arj'     => 'application/octet-stream',
        'asf'     => 'video/x-ms-asf',
        'asm'     => 'text/x-asm',
        'asp'     => 'text/asp',
        'au'      => 'audio/basic',
        'avi'     => 'video/avi',
        'avs'     => 'video/avs-video',
        'bin'     => 'application/octet-stream',
        'bmp'     => 'image/bmp',
        'bsh'     => 'application/x-bsh',
        'bz'      => 'application/x-bzip',
        'bz2'     => 'application/x-bzip2',
        'c'       => 'text/x-c',
        'c++'     => 'text/plain',
        'cat'     => 'application/vnd.ms-pki.seccat',
        'cc'      => 'text/plain',
        'cer'     => 'application/x-x509-ca-cert',
        'class'   => 'application/java',
        'conf'    => 'text/plain',
        'cpio'    => 'application/x-cpio',
        'cpp'     => 'text/x-c',
        'cpt'     => 'application/mac-compactpro',
        'csh'     => 'application/x-csh',
        'css'     => 'text/css',
        'def'     => 'text/plain',
        'der'     => 'application/x-x509-ca-cert',
        'dll'     => 'application/octet-stream',
        'dms'     => 'application/octet-stream',
        'doc'     => 'application/msword',
        'dot'     => 'application/msword',
        'dump'    => 'application/octet-stream',
        'dvi'     => 'application/x-dvi',
        'dwg'     => 'application/acad',
        'el'      => 'text/x-script.elisp',
        'eps'     => 'application/postscript',
        'etx'     => 'text/x-setext',
        'exe'     => 'application/octet-stream',
        'fdf'     => 'application/vnd.fdf',
        'fli'     => 'video/x-fli',
        'fpx'     => 'image/vnd.fpx',
        'gif'     => 'image/gif',
        'gtar'    => 'application/x-gtar',
        'gz'      => 'application/x-gzip',
        'gzip'    => 'application/x-gzip',
        'hdf'     => 'application/x-hdf',
        'help'    => 'application/x-helpfile',
        'hlp'     => 'application/x-winhelp',
        'hqx'     => 'application/mac-binhex',
        'hta'     => 'application/hta',
        'htm'     => 'text/html',
        'html'    => 'text/html',
        'htmls'   => 'text/html',
        'htx'     => 'text/html',
        'ico'     => 'image/x-icon',
        'imap'    => 'application/x-httpd-imap',
        'inf'     => 'application/inf',
        'java'    => 'text/x-java-source',
        'jfif'    => 'image/jpeg',
        'jpe'     => 'image/jpeg',
        'jpeg'    => 'image/jpeg',
        'jpg'     => 'image/jpeg',
        'js'      => 'application/x-javascript',
        'json'    => 'application/json',
        'kar'     => 'music/x-karaoke',
        'ksh'     => 'application/x-ksh',
        'latex'   => 'application/x-latex',
        'lha'     => 'application/octet-stream',
        'list'    => 'text/plain',
        'log'     => 'text/plain',
        'lsp'     => 'application/x-lisp',
        'lst'     => 'text/plain',
        'ltx'     => 'application/x-latex',
        'lzh'     => 'application/octet-stream',
        'm1v'     => 'video/mpeg',
        'm2a'     => 'audio/mpeg',
        'm2v'     => 'video/mpeg',
        'm3u'     => 'audio/x-mpequrl',
        'man'     => 'application/x-troff-man',
        'mcd'     => 'application/mcad',
        'mht'     => 'message/rfc822',
        'mhtml'   => 'message/rfc822',
        'mid'     => 'application/x-midi',
        'midi'    => 'application/x-midi',
        'mime'    => 'message/rfc822',
        'mm'      => 'application/base64',
        'mod'     => 'audio/x-mod',
        'moov'    => 'video/quicktime',
        'mov'     => 'video/quicktime',
        'mp2'     => 'audio/x-mpeg',
        'mp3'     => 'audio/mpeg3',
        'mpe'     => 'video/mpeg',
        'mpeg'    => 'video/mpeg',
        'mpg'     => 'video/mpeg',
        'mpga'    => 'audio/mpeg',
        'mpp'     => 'application/vnd.ms-project',
        'mv'      => 'video/x-sgi-movie',
        'pas'     => 'text/pascal',
        'pbm'     => 'image/x-portable-bitmap',
        'pct'     => 'image/x-pict',
        'pcx'     => 'image/x-pcx',
        'pdf'     => 'application/pdf',
        'pgm'     => 'image/x-portable-graymap',
        'php'     => 'text/plain',
        'pic'     => 'image/pict',
        'pict'    => 'image/pict',
        'pl'      => 'text/plain',
        'png'     => 'image/png',
        'pot'     => 'application/vnd.ms-powerpoint',
        'ppa'     => 'application/vnd.ms-powerpoint',
        'pps'     => 'application/vnd.ms-powerpoint',
        'ppt'     => 'application/vnd.ms-powerpoint',
        'ps'      => 'application/postscript',
        'psd'     => 'application/octet-stream',
        'py'      => 'text/x-script.phyton',
        'qt'      => 'video/quicktime',
        'ra'      => 'audio/x-realaudio',
        'ram'     => 'audio/x-pn-realaudio',
        'rm'      => 'application/vnd.rn-realmedia',
        'rt'      => 'text/richtext',
        'rtf'     => 'application/x-rtf',
        'rtx'     => 'text/richtext',
        'rv'      => 'video/vnd.rn-realvideo',
        'sea'     => 'application/octet-stream',
        'sgm'     => 'text/x-sgml',
        'sgml'    => 'text/x-sgml',
        'sh'      => 'application/x-sh',
        'shtml'   => 'text/html',
        'snd'     => 'audio/basic',
        'so'      => 'application/octet-stream',
        'swf'     => 'application/x-shockwave-flash',
        'tar'     => 'application/x-tar',
        'tcl'     => 'application/x-tcl',
        'tcsh'    => 'text/x-script.tcsh',
        'tex'     => 'application/x-tex',
        'texi'    => 'application/x-texinfo',
        'texinfo' => 'application/x-texinfo',
        'text'    => 'text/plain',
        'tgz'     => 'application/x-compressed',
        'tif'     => 'image/tiff',
        'tiff'    => 'image/tiff',
        'txt'     => 'text/plain',
        'vcs'     => 'text/x-vcalendar',
        'voc'     => 'audio/voc',
        'vrml'    => 'application/x-vrml',
        'wav'     => 'audio/x-wav',
        'wbmp'    => 'image/vnd.wap.wbmp',
        'wml'     => 'text/vnd.wap.wml',
        'word'    => 'application/msword',
        'xbm'     => 'image/x-xbitmap',
        'xht'     => 'application/xhtml+xml',
        'xhtml'   => 'application/xhtml+xml',
        'xla'     => 'application/x-msexcel',
        'xlb'     => 'application/vnd.ms-excel',
        'xlc'     => 'application/vnd.ms-excel',
        'xls'     => 'application/vnd.ms-excel',
        'xm'      => 'audio/xm',
        'xml'     => 'text/xml',
        'xpm'     => 'image/xpm',
        'x-png'   => 'image/png',
        'zip'     => 'application/x-zip-compressed',
        'zsh'     => 'text/x-script.zsh',
    ];

    #endregion


    #region // - - -   P U B L I C   S T A T I C   M E T H O D S   - - - - - - - - - - - - - - - - - -

    /**
     * Returns the MIME type, associated with the defined file name extension.
     *
     * @param string $extension The file name extension (with or without the leading dot)
     *
     * @return string            Returns the MIME type
     */
    public static function GetByExtension( string $extension ): string
    {

        $normalizedExtension = \strtolower( \ltrim( $extension, '.' ) );

        if ( isset( static::$mimeTypes[ $normalizedExtension ] ) )
        {
            return static::$mimeTypes[ $normalizedExtension ];
        }

        return 'application/octet-stream';

    }

    /**
     * Returns the MIME type, associated with the file name extension of the defined file name/path
     *
     * @param string $fileName The file name/path
     *
     * @return string Returns the MIME type.
     */
    public static function GetByFileName( string $fileName ): string
    {

        return static::GetByExtension( File::GetExtension( $fileName ) );

    }

    /**
     * Returns if the defined MIME type represents an image type.
     *
     * @param string $mimeType The MIME type to check.
     *
     * @return boolean
     */
    public static function IsImageType( string $mimeType ): bool
    {

        return \str_starts_with( $mimeType, 'image/' );

    }

    /**
     * Returns if the defined file name extension points to an image file type.
     *
     * @param string $extension The file name extension (with or without the leading dot.
     *
     * @return boolean
     */
    public static function IsImageTypeExtension( string $extension ): bool
    {

        return static::IsImageType( static::GetByExtension( $extension ) );

    }

    /**
     * Returns if the defined file name/path points to an image file type.
     *
     * @param string $fileName File (name or path)
     *
     * @return boolean
     */
    public static function IsImageTypeFileName( string $fileName ): bool
    {

        return static::IsImageType( static::GetByFileName( $fileName ) );

    }

    #endregion


}

