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
 * A static folder (directory) helping class.
 */
class Folder
{


    #region // = = =   C O N S T R U C T O R   = = = = = = = = = = = = = = = = = = = = = = = = = = = =

    /**
     * Inits a new instance.
     */
    private function __construct() { }

    #endregion


    #region // - - -   P U B L I C   S T A T I C   M E T H O D S   - - - - - - - - - - - - - - - - - -

    /**
     * Goes the count of $upLevel up inside the defined folder. Each level up goes 1 level up to the parent folder.
     *
     * e.g.:
     *
     * <code>\Kado\IO\Folder::Up( '/is/var/www', 2, '' );   // results in: '/is'</code>
     *
     * @param string $folder  The base folder
     * @param int    $upLevel How many folder levels we should go up? (Defaults to 1)
     * @param string $endChar Append this character to the end of the resulting folder path. (Defaults to '')
     *
     * @return string
     */
    public static function Up( string $folder, int $upLevel = 1, string $endChar = '' ): string
    {

        $folder = \dirname( $folder, $upLevel );

        return $folder . $endChar;

    }

    /**
     * Checks if the defined folder exists. If so, $folder is returned normalized. If not so,
     * the first existing parent folder is returned normalized.
     *
     * @param string $folder
     *
     * @return string
     */
    public static function GetFirstExisting( string $folder ): string
    {

        // Remove directory separator from $folder end
        $folder = \rtrim( $folder, '\\/' );

        if ( \str_contains( $folder, '/' ) && \str_contains( $folder, '\\' ) )
        {
            // Uses mixed directory separators, normalize it
            if ( \DIRECTORY_SEPARATOR == '\\' )
            {
                $folder = \str_replace( '/', '\\', $folder );
            }
            else
            {
                $folder = \str_replace( '\\', '/', $folder );
            }
        }

        // Get the current sub folder level
        $level =
            \count(
                \explode(
                    \DIRECTORY_SEPARATOR,
                    \ltrim( $folder, '\\/' )
                )
            );

        for ( $i = 0; $i < $level && !\is_dir( $folder ); ++$i )
        {
            $f = \dirname( $folder );
            if ( empty( $f ) || $f == $folder )
            {
                return '/';
            }
            $folder = $f;
        }

        \clearstatcache();

        return $folder;

    }

    /**
     * Checks, if the folder can be created. !Attention! If the folder already exists, FALSE is returned!
     *
     * @param string $folder_path The folder to check.
     *
     * @return boolean
     */
    public static function CanCreate( string $folder_path ): bool
    {

        if ( \is_dir( $folder_path ) )
        {
            return false;
        }

        $fip = static::GetFirstExisting( $folder_path );

        return \is_writable( $fip );

    }

    /**
     * Creates a new folder with defined mode. The mode only works on unixoid systems.
     *
     * @param string  $folder The folder to create.
     * @param integer $mode   Mode for new folder (0700) only used for unixoid systems
     *
     * @throws IOException If creation fails
     */
    public static function Create( string $folder, int $mode = 0700 ) : void
    {

        if ( \is_dir( $folder ) )
        {
            // No folder = do nothing
            return;
        }

        if ( ! static::CanCreate( $folder ) )
        {
            // No rights to create the required folder
            throw new IOException(
                $folder,
                'Creation of folder fails cause base folder is not writable!'
            );
        }

        try
        {
            $res = \mkdir( $folder, $mode, true );
        }
        catch ( \Throwable $ex )
        {
            // Folder creation fails
            throw new IOException(
                $folder,
                'Folder creation fails!',
                \E_USER_ERROR,
                $ex
            );
        }

        if ( ! $res || ! \is_dir( $folder ) )
        {
            // Folder creation fails
            throw new IOException(
                $folder,
                'Unknown error while executing folder creation.'
            );
        }

        \clearstatcache();

    }

    /**
     * Deletes the defined folder recursive with all contained files and sub folders.
     *
     * @param string  $folder The folder to delete.
     * @param boolean $clear  Clear only all folder contents and don't delete the main folder? (Defaults to FALSE)
     *
     * @throws IOException
     */
    public static function Delete( string $folder, bool $clear = false ) : void
    {

        if ( empty( $folder ) && ! \is_dir( $folder ) )
        {
            // No folder = we are done here
            return;
        }

        // Ensure the folder ends with a directory separator.
        $folder = \rtrim( $folder, '\\/' ) . \DIRECTORY_SEPARATOR;

        // Start getting all elements of the defined folder

        $openDir = \opendir( $folder );
        // ignore . and .. (it's always the first and second
        \readdir( $openDir );
        \readdir( $openDir );
        while ( false !== ( $item = \readdir( $openDir ) ) )
        {
            // The absolute item path
            $path = $folder . $item;
            if ( ! \is_dir( $path ) )
            {
                // $path is a file
                File::Delete( $path );
            }
            else
            {
                // $path is a folder. Delete it recursively
                static::Delete( $path );
            }
        }
        \closedir( $openDir );

        if ( !$clear )
        {
            // $folder should not be deleted, only emptied. We are done here
            return;
        }

        // Finally delete $folder
        try
        {
            \rmdir( $folder );
        }
        catch ( \Throwable $ex )
        {
            throw new IOException(
                $folder,
                'Could not delete the defined folder.',
                \E_USER_ERROR,
                $ex
            );
        }

    }

    /**
     * Returns all file paths inside the defined folder.
     *
     * @param string  $folder
     * @param boolean $recursive Also include sub folders? (Defaults to FALSE)
     *
     * @return array
     */
    public static function ListAllFiles( string $folder, bool $recursive = false ): array
    {

        // Init the array that should contain the resulting file paths
        $files = [];

        if ( ! @\is_dir( $folder ) )
        {
            // No folder = we are done here
            return $files;
        }

        if ( ! $recursive )
        {
            // List only files directly contained inside $folder (none from sub folders)

            // Open the directory pointer
            $d = \dir( $folder );
            // Ignore . and ..
            $d->read();
            $d->read();
            // loop the rest
            while ( false !== ( $entry = $d->read() ) )
            {
                $tmp = Path::Combine( $folder, $entry );
                if ( !\is_file( $tmp ) )
                {
                    // Ignore sub folders
                    continue;
                }
                $files[] = $tmp;
            }
            $d->close();

            return $files;
        }

        // List all files, also from sub folders
        static::_listRecursive( $files, $folder );

        return $files;

    }

    /**
     * Returns all files, matching the defined filter.
     *
     * If $recursive is TRUE, it also includes all sub folders.
     *
     * If the filter is a callback function/method, so it must accept 2 parameters.
     *
     * - string $itemName The name of the current filter item
     * - string $itemPath The absolute path of the item.
     *
     * @param string          $folder    The folder
     * @param callable|string $filter    Regex or callback|callable for filtering files
     * @param boolean         $recursive Include sub folders? (Defaults to FALSE)
     *
     * @return array
     */
    public static function ListFilteredFiles( string $folder, callable|string $filter, bool $recursive = false ): array
    {

        // Init the array that should contain the resulting file paths
        $files = [];

        if ( ! \is_dir( $folder ) )
        {
            // No folder = we are done here
            return $files;
        }

        if ( ! $recursive )
        {
            // List only files directly contained inside $folder (none from sub folders)

            // Open the directory pointer
            $d = \dir( $folder );
            while ( false !== ( $entry = $d->read() ) )
            {
                if ( $entry == '.' || $entry == '..' )
                {
                    // Ignore . and ..
                    continue;
                }
                $tmp = Path::Combine( $folder, $entry );
                if ( ! \is_file( $tmp ) )
                {
                    continue;
                }
                if ( \is_callable( $filter ) )
                {
                    if ( \call_user_func( $filter, $entry, $tmp ) )
                    {
                        $files[] = $tmp;
                    }
                    continue;
                }
                try
                {
                    if ( ! \preg_match( $filter, $entry ) )
                    {
                        continue;
                    }
                    $files[] = $tmp;
                }
                catch ( \Exception ) { }
            }
            $d->close();

            return $files;
        }

        static::_listRecursiveFiltered( $files, $filter, $folder );

        return $files;

    }

    /**
     * Returns the real path of the defined folder. If the folder is defined as an absolute path, it's returned as it,
     * only normalized. Otherwise, the $basePath is used to make the folder absolute.
     *
     * Its no checked, if the folder or path exists!
     *
     * Examples:
     *
     * <code>
     * echo "'" . \Kado\IO\Folder::GetRealPath('../xyz', '/abc/def') . "'";
     * # outputs '/abc/xyz'
     * echo "'" . \Kado\IO\Folder::GetRealPath('./xyz', '/abc/def') . "'";
     * # outputs '/abc/def/xyz'
     * echo "'" . \Kado\IO\Folder::GetRealPath('/xyz', '/abc/def') . "'";
     * # outputs '/xyz'
     * echo "'" . \Kado\IO\Folder::GetRealPath('C:/xyz', 'C:/abc/def') . "'";
     * # outputs 'C:/xyz'
     * echo "'" . \Kado\IO\Folder::GetRealPath('../../xyz', '/abc/def') . "'";
     * # outputs '/xyz'
     * </code>
     *
     * @param string $folder   The folder
     * @param string $basePath The base path used if the $folder is relative. If it's empty, getcwd() is used.
     *
     * @return string
     */
    public static function GetRealPath( string $folder, string $basePath ): string
    {

        if ( \strlen( $basePath ) < 1 )
        {
            // If no base path is defined use path given by getcwd()
            $basePath = getcwd();
        }

        if ( false === $basePath )
        {
            return '';
        }

        // Measure the length of $folder
        $flen = \strlen( $folder );

        // Switch base path to OS directory separator and remove trailing directory separators
        $basePath = \rtrim( Path::Normalize( $basePath ), \DIRECTORY_SEPARATOR );

        if ( $flen < 1 )
        {
            // return the base path if the folder is empty.
            return $basePath;
        }

        // Switch folder to OS directory separator
        $folder = Path::Normalize( $folder );

        if ( $folder[ 0 ] == '/' )
        {
            // $folder is an absolute unix path. return it
            return $folder;
        }

        if ( $flen < 2 )
        {
            if ( $folder == '.' )
            {
                // $folder is only a dot '.'
                return $basePath;
            }

            // $folder is a single character
            return $basePath . \DIRECTORY_SEPARATOR . \trim( $folder, \DIRECTORY_SEPARATOR );
        }

        if ( $flen == 2 )
        {
            if ( \IS_WIN && $folder[ 1 ] == ':' )
            {
                // If its only like C: in Windows systems, append a \
                return $folder . \DIRECTORY_SEPARATOR;
            }
            if ( $folder[ 0 ] == '.' && $folder[ 1 ] == '.' )
            {
                return \dirname( $basePath );
            }

            // Return normally combined
            return $basePath . \DIRECTORY_SEPARATOR . \trim( $folder, \DIRECTORY_SEPARATOR );
        }

        if ( ( $folder[ 1 ] == ':' && $folder[ 2 ] == '/' )
             || ( $folder[ 1 ] == '\\' && $folder[ 2 ] == '\\' ) )
        {
            // Absolute Windows folders. Return it.
            return $folder;
        }

        // The next lines does not handle some /../ inside the folder

        // Remove all leading ../ or ..\
        while ( \str_starts_with( $folder, '..' . \DIRECTORY_SEPARATOR ) )
        {
            $basePath = \dirname( $basePath );
            $folder = \substr( $folder, 3 );
        }

        return $basePath . \DIRECTORY_SEPARATOR . \trim( $folder, '/' );

    }

    /**
     * Removes all contents from defined folder (empties it)
     *
     * @param string $folder
     *
     * @throws IOException
     */
    public static function Clear( string $folder ) : void
    {

        static::Delete( $folder, true );
    }

    /**
     * Moves all folder path elements (files + sub folders) from $sourceFolder to $targetFolder.
     *
     * The target folder will be created if it not exists.
     *
     * The source folder it empty after this action.
     *
     * @param string $sourceFolder The source folder.
     * @param string $targetFolder The target folder.
     * @param int    $tFolderMode  The mode of the target folder if it must be created. (Defaults to 0700)
     * @param bool   $clearTarget  Clear the target folder if it has some contents (Defaults to FALSE)
     *
     * @throws IOException
     * @uses   Folder::Copy Uses internally the Copy method and clears after it the $sourceFolder
     */
    public static function MoveContents(
        string $sourceFolder, string $targetFolder, int $tFolderMode = 0700, bool $clearTarget = false ) : void
    {

        static::Copy( $sourceFolder, $targetFolder, $tFolderMode, $clearTarget );
        static::Clear( $sourceFolder );

    }

    /**
     * Moves the $sourceFolder to the $targetFolder. $sourceFolder will be deleted.
     *
     * @param string $sourceFolder The source folder.
     * @param string $targetFolder The target folder.
     * @param int    $tFolderMode  The mode of the target folder if it must be created. (Defaults to 0700)
     * @param bool   $clearTarget  Clear the target folder if it has some contents (Defaults to FALSE)
     *
     * @throws IOException
     */
    public static function Move(
        string $sourceFolder, string $targetFolder, int $tFolderMode = 0700, bool $clearTarget = false ) : void
    {

        static::Copy( $sourceFolder, $targetFolder, $tFolderMode, $clearTarget );
        static::Delete( $sourceFolder );

    }

    /**
     * Copies all folder path elements (files + sub folders) from $sourceFolder to $targetFolder.
     *
     * @param string $sourceFolder The source folder.
     * @param string $targetFolder The target folder.
     * @param int    $tFolderMode  The mode of the target folder if it must be created. (Defaults to 0700)
     * @param bool   $clearTarget  Clear the target folder if it has some contents (Defaults to FALSE)
     *
     * @throws IOException
     */
    public static function Copy(
        string $sourceFolder, string $targetFolder, int $tFolderMode = 0700, bool $clearTarget = false ) : void
    {

        // Remove trailing directory separators
        $sourceFolder = \rtrim( $sourceFolder, '\\/' );
        $targetFolder = \rtrim( $targetFolder, '\\/' );

        if ( ! \is_dir( $sourceFolder ) )
        {
            // If the source folder not exists, stop here…
            throw new IOException(
                $sourceFolder,
                'Can not copy folder contents to another folder if defined source folder dont exists!'
            );
        }

        if ( \is_dir( $targetFolder ) )
        {
            // Target exists
            if ( $clearTarget )
            {
                // Clear the target
                static::Clear( $targetFolder );
            }
        }
        else
        {
            // Target folder don't exist, create it
            static::Create( $targetFolder, $tFolderMode );
        }

        // Start reading the folder elements
        $openDir = \opendir( $sourceFolder );

        // Ignore '.' and '..' items
        \readdir( $openDir );
        \readdir( $openDir );

        // loop all other items
        while ( false !== ( $item = \readdir( $openDir ) ) )
        {
            $spath = $sourceFolder . '/' . $item;
            $tpath = $targetFolder . '/' . $item;
            if ( !\is_dir( $spath ) )
            {
                // It's a file, copy it
                File::Copy( $spath, $tpath );
            }
            else
            {
                // It's a folder, copy it
                static::Copy( $spath, $tpath, $tFolderMode );
            }
        }

        // End reading the folder elements
        \closedir( $openDir );

    }

    /**
     * Zips all folder contents to defined ZIP archive.
     *
     * @param string      $sourceFolder The folder to zip
     * @param string      $zipFile      The ZIP archive destination file
     * @param string|null $zFolderName  If you zip it inside a special folder inside the archive, name the folder here.
     * @param boolean     $overwrite    Overwrite the archive if it exists? (Defaults to TRUE)
     *
     * @throws FileAlreadyExistsException If the ZIP exists, and overwriting is disabled.
     * @throws FileNotFoundException
     * @throws IOException In all other error cases, while ZIP writing.
     */
    public static function Zip(
        string $sourceFolder, string $zipFile, ?string $zFolderName = null, bool $overwrite = true ) : void
    {

        $oldFile = null;
        if ( ! \class_exists( 'ZipArchive' ) )
        {
            throw new IOException(
                $sourceFolder,
                '\Kado\IO\Folder::Zip() fails with no ZIP-Support.'
            );
        }
        if ( \file_exists( $zipFile ) )
        {
            if ( ! $overwrite )
            {
                throw new FileAlreadyExistsException(
                    $zipFile,
                    'Overwriting the ZIP-file is not allowed by code.'
                );
            }
            $oldFile = $zipFile . '.old';
            File::Move( $zipFile, $oldFile );
        }
        $zip = new \ZipArchive();
        if ( true !== ( $res = $zip->open( $zipFile, \ZipArchive::CREATE ) ) )
        {
            if ( !empty( $oldFile ) )
            {
                File::Move( $oldFile, $zipFile );
            }
            static::___handWriteError( $res, $zipFile );
        }
        if ( empty( $zFolderName ) )
        {
            $tmp = \preg_split( '~[\\\\/]~', \dirname( $zipFile ) );
            $zFolderName = $tmp[ \count( $tmp ) - 1 ];
        }
        $zip->addEmptyDir( $zFolderName );
        $openDir = \opendir( $sourceFolder );
        \readdir( $openDir );
        \readdir( $openDir );
        while ( false !== ( $item = \readdir( $openDir ) ) )
        {
            $itemPath = $sourceFolder . '/' . $item;
            static::___zipItem( $item, $itemPath, $zip, $zFolderName );
        }
        \closedir( $openDir );
        $zip->close();
    }

    #endregion


    #region // - - -   P R I V A T E   S T A T I C   M E T H O D S   - - - - - - - - - - - - - - - - -

    /**
     * @param  $res
     * @param  $zipFile
     *
     * @throws IOException
     */
    private static function ___handWriteError( $res, $zipFile )
    {

        throw new IOException(
            $zipFile,
            'Zip file could not be created cause ' . File::GetZipArchiveError( $res ) );

    }

    /**
     * @param             $item
     * @param string      $itemPath
     * @param \ZipArchive $zip
     * @param string      $zFolderName
     */
    private static function ___zipItem( $item, string $itemPath, \ZipArchive $zip, string $zFolderName ) : void
    {

        if ( \is_dir( $itemPath ) )
        {
            $zFolderName .= '/' . $item;
            $zip->addEmptyDir( $zFolderName );
            $openDir = \opendir( $itemPath );
            while ( false !== ( $itm = \readdir( $openDir ) ) )
            {
                if ( $itm == '.' || $itm == '..' )
                {
                    continue;
                }
                $itmPath = $itemPath . '/' . $itm;
                static::___zipItem( $itm, $itmPath, $zip, $zFolderName );
            }
        }
        else
        {
            $zip->addFile( $itemPath, $zFolderName . '/' . $item );
        }
    }

    /**
     * @param $res
     * @param $folder
     */
    private static function _listRecursive( &$res, $folder ) : void
    {

        $d = \dir( $folder );
        $d->read();
        $d->read();
        while ( false !== ( $entry = $d->read() ) )
        {
            $tmp = Path::Combine( $folder, $entry );
            if ( \is_dir( $tmp ) )
            {
                static::_listRecursive( $res, $tmp );
            }
            else
            {
                $res[] = $tmp;
            }
        }
        $d->close();
    }

    /**
     * @param $res
     * @param $filter
     * @param $folder
     */
    private static function _listRecursiveFiltered( &$res, $filter, $folder ) : void
    {

        $d = \dir( $folder );
        $d->read();
        $d->read();
        while ( false !== ( $entry = $d->read() ) )
        {
            $tmp = Path::Combine( $folder, $entry );
            if ( ! \is_file( $tmp ) )
            {
                static::_listRecursiveFiltered( $res, $filter, $tmp );
                continue;
            }
            if ( \is_callable( $filter ) )
            {
                if ( \call_user_func( $filter, $entry, $tmp ) )
                {
                    $res[] = $tmp;
                }
                continue;
            }
            try
            {
                if ( ! \preg_match( $filter, $entry ) )
                {
                    continue;
                }
                $res[] = $tmp;
            }
            catch ( \Exception ) { }
        }
        $d->close();
    }

    #endregion


}

