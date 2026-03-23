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


use \Kado\KadoException;


/**
 * This class defines an exception, used as base exception to all IO exceptions.
 *
 * It extends from {@see KadoException}.
 *
 * @since v0.1.0
 */
class IOException extends KadoException
{


    #region // = = =   C O N S T R U C T O R   = = = = = = = = = = = = = = = = = = = = = = = = = = = =

    /**
     * Init a new instance.
     *
     * @param string          $path     The path, associated with the error.
     * @param string|null     $message  optional error message.
     * @param string|int      $code     The optional error code (Defaults to \E_USER_ERROR)
     * @param \Throwable|null $previous optional previous exception
     */
    public function __construct(
        protected string $path, ?string $message = null, string|int $code = 256, ?\Throwable $previous = null )
    {

        parent::__construct(
            \sprintf( 'There was a error with path [%s]!', $path ) . static::appendMessage( $message ),
            $code,
            $previous
        );

    }

    #endregion


    #region // - - -   P U B L I C   M E T H O D S   - - - - - - - - - - - - - - - - - - - - - - - - -

    /**
     * Returns the path that was error depending.
     *
     * @return string
     */
    public final function getPath(): string
    {

        return $this->path;

    }

    #endregion


}

