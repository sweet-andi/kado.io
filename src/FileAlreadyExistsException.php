<?php
/**
 * @author       Sweet Andi
 * @copyright  © 2026, Sweet Andi
 * @package      Kado\IO
 * @since        2026-03-23
 * @version      1.0.0
 */


declare( strict_types = 1 );


namespace Kado\IO;


/**
 * This class defines an exception, thrown if a file already exists, but it should not exist.
 *
 * It extends from {@see IOException}.
 */
class FileAlreadyExistsException extends IOException
{


    #region // = = =   C O N S T R U C T O R   = = = = = = = = = = = = = = = = = = = = = = = = = = = =

    /**
     * Init a new instance.
     *
     * @param string          $file     The already existing file
     * @param string|null     $message  The optional error message
     * @param string|int      $code     The optional error code (Default to \E_USER_ERROR)
     * @param \Throwable|null $previous optional previous exception
     */
    public function __construct(
        string $file, ?string $message = null, string|int $code = \E_USER_ERROR, ?\Throwable $previous = null)
    {

        parent::__construct(
            $file,
            'The File does already exist.' . static::appendMessage($message),
            $code,
            $previous
        );

    }

    #endregion


}

