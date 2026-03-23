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
 * This exception should be used if accessing a folder for reading and/or writing fails.
 *
 * The class extends from {@see IOException}.
 */
class FolderAccessException extends IOException
{


    #region // = = =   C O N S T A N T S   = = = = = = = = = = = = = = = = = = = = = = = = = = = = = =

    /**
     * Reading folder access.
     */
    public const string ACCESS_READ = 'read';

    /**
     * Writing folder access.
     */
    public const string ACCESS_WRITE = 'write';

    /**
     * Reading and writing folder access.
     */
    public const string ACCESS_READWRITE = 'read and write';

    /**
     * Creating folder access.
     */
    public const string ACCESS_CREATE = 'create';

    /**
     * Deleting folder access.
     */
    public const string ACCESS_DELETE = 'delete';

    #endregion


    #region // = = =   C O N S T R U C T O R   = = = = = = = = = = = = = = = = = = = = = = = = = = = =

    /**
     * Inits a new instance.
     *
     * @param string          $folder   The folder where accessing fails.
     * @param string          $access   The access type (see \Kado\IO\FolderAccessException::ACCESS_* class constants)
     * @param string|null     $message  The optional error message
     * @param string|int      $code     optional error code (Defaults to \E_USER_ERROR)
     * @param \Throwable|null $previous Optional previous exception.
     */
    public function __construct(
        string     $folder, private readonly string $access = self::ACCESS_READ, ?string $message = null,
        string|int $code = 256, ?\Throwable $previous = null )
    {

        parent::__construct(
            $folder,
            \sprintf( 'Could not %s folder.', $access ) . static::appendMessage( $message ),
            $code,
            $previous
        );

    }

    #endregion


    #region // - - -   P U B L I C   M E T H O D S   - - - - - - - - - - - - - - - - - - - - - - - - -

    /**
     * Returns the error folder access type (see \Kado\IO\FolderAccessException::ACCESS_* class constants)
     *
     * @return string
     */
    public final function getAccessType(): string
    {

        return $this->access;
    }

    #endregion


    #region // - - -   P U B L I C   S T A T I C   M E T H O D S   - - - - - - - - - - - - - - - - - -

    /**
     * Inits a new \Kado\IO\FolderAccessException for folder read mode.
     *
     * @param string          $folder   The folder where reading fails.
     * @param string|null     $message  The optional error message.
     * @param integer         $code     optional error code (Defaults to \E_USER_ERROR)
     * @param \Throwable|null $previous Optional previous exception.
     * @return FolderAccessException
     */
    public static function Read(
        string $folder, ?string $message = null, int $code = \E_USER_ERROR, ?\Throwable $previous = null )
    : FolderAccessException
    {

        return new FolderAccessException (
            $folder,
            FolderAccessException::ACCESS_READ,
            $message,
            $code,
            $previous
        );

    }

    /**
     * Inits a new \Kado\IO\FolderAccessException for folder write mode.
     *
     * @param string          $folder   The folder where reading fails.
     * @param string|null     $message  The optional error message.
     * @param integer         $code     optional error code (Defaults to \E_USER_ERROR)
     * @param \Throwable|null $previous Optional previous exception.
     * @return FolderAccessException
     */
    public static function Write(
        string $folder, ?string $message = null, int $code = \E_USER_ERROR, ?\Throwable $previous = null )
    : FolderAccessException
    {

        return new FolderAccessException (
            $folder,
            FolderAccessException::ACCESS_WRITE,
            $message,
            $code,
            $previous
        );

    }

    #endregion


}

