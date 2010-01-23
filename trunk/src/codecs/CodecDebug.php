<?php

/**
 * CodecDebug is a singleton class to aid Codec debugging.  It buffers debug
 * info comprising the input to a Codec encode/decode method, as single UTF-32
 * encoded characters, as well as the final output from the Codec method.  The
 * debug info is logged immediately before the Codec method returns its value
 * and the buffer is cleared at that time.
 *
 * Codec methods should call setInitial(), passing the value of its input
 * parameter, as soon as possible.
 * decodeCharacter methods call addToDecode and encodeCharacter methods call
 * addToEncode to add Single UTF-32 encoded characters to the buffer.
 * Codec methods pass their return value to either outputDecoded (for decode
 * methods) or outputEncoded (encode methods) to flush the debug info to a
 * specific instance of Logger.
 */

define('LOG', 'CodecDebug');

class CodecDebug
{
    private $initial;
    private $logger;
    private $buf = null;
    private $allowRecurse = true;

    private static $instance;
    private function __clone() {}
    private function __construct() {}
    public static function getInstance()
    {
        if (!self::$instance)
        {
            self::$instance = new CodecDebug();
        }
        return self::$instance;
    }


    /**
     * setInitial() should be called at the start of the function which calls for the final
     * output e.g. in Codec::encode() or decode().
     * setInitial() sets $this->initial with the supplied string codec input
     * which is used as a weak way of preventing premature logging which would
     * otherwise happen when using encoding methods within CodecDebug.
     *
     * @param string $initial codec input
     */
    public function setInitial($initial)
    {
        if (! $this->allowRecurse) return;
        if ($this->initial !== null)
        {
            return;
        }
        $this->initial = $initial;
    }


    /**
     * resets $this->initial.
     */
    private function resetInitial()
    {
        $this->initial = null;
    }


    /**
     * addNormalized is called by addToDecode and addToEncode and adds a UTF-32
     * encoded character (and some extra debug info) to the buffer.
     *
     * @param string $charNormalizedEncoding is a UTF-32 encoded character.
     * @return null
     */
    public function addNormalized($charNormalizedEncoding)
    {
        // TODO - it's not very pretty and i'm also worried that var_export does strange things.
        ob_start();
        var_dump($charNormalizedEncoding);
        $dumpedVar = ob_get_clean();
        $matches=array();
        if (! preg_match('/\(length=([0-9]+)\)/', $dumpedVar, $matches)) {
            $matches[1] = strtok(  stristr($dumpedVar, "("),  '"'  );
        }
        $this->buf .= "Normalized codec input: " .
        $matches[1] .
                      " bytes [" .
        substr( var_export($charNormalizedEncoding, true), 0 ) .
                      "]<br>\n";
    }


    /**
     * Called by Codec::decodeCharacter methods, addToDecode sets-up the buffer
     * to begin with a caller trace and passes the supplied character to
     * addNormalized.
     *
     * @param string $charNormalizedEncoding is a UTF-32 encoded character.
     * @return null
     */
    public function addToDecode($charNormalizedEncoding)
    {
        if (! ESAPI::getLogger(LOG)->isDebugEnabled() || ! $this->allowRecurse) return;
        if ($this->buf === null)
        {
            $caller = null;
            try {
                $caller = $this->_shortTrace();
            } catch (Exception $e) {
                $caller = "Decoding";
            }
            $this->buf = $caller.":<br />\n"; // ;)

        }
        $this->addNormalized($charNormalizedEncoding);
    }


    /**
     * Called by Codec::encodeCharacter methods, addToEncode sets-up the buffer
     * to begin with a caller trace and passes the supplied parameter to
     * addNormalized.
     *
     * @param string $charNormalizedEncoding is a UTF-32 encoded character.
     * @return null
     */
    public function addToEncode($charNormalizedEncoding)
    {
        if (! ESAPI::getLogger(LOG)->isDebugEnabled() || ! $this->allowRecurse) return;
        if ($this->buf === null)
        {
            $caller = null;
            try {
                $caller = $this->_shortTrace();
            } catch (Exception $e) {
                $caller = "Encoding";
            }
            $this->buf = $caller.":<br />\n"; // ;)

        }
        $this->addNormalized($charNormalizedEncoding);
    }


    /**
     * outputEncoded appends the final encoded string (encoded for HTML) to the
     * contents of $this->buf and logs this debugging output before resetting
     * the CodecDebug instance.
     *
     * @param string $initial is the original unencoded input string.
     * @param string $encoded is the final encoded string.
     * @return null
     */
    public function outputEncoded($initial, $encoded)
    {
        if (! ESAPI::getLogger(LOG)->isDebugEnabled() || ! $this->allowRecurse) return;
        if ($this->buf === null)
        {
            $this->resetInitial();
            return; // the codec being tested has not added any normalised inputs.
        }
        if ($this->initial != $initial)
        {
            return; // TODO does this need removing now?
        }

        $this->allowRecurse = false;
        $output = $this->buf . "Encoded: [" . ESAPI::getEncoder()->encodeForHTML($encoded) . "]";

        ESAPI::getLogger(LOG)->debug(new EventType("Codec encode() output"), true, $output);
        $this->allowRecurse = true;

        $this->buf = null;
        $this->resetInitial();
    }

    /**
     * outputDecoded appends the final decoded string (encoded for HTML) to the
     * contents of $this->buf and logs this debugging output before resetting
     * the CodecDebug instance.
     *
     * @param string $initial is the original encoded input string.
     * @param string $decoded is the final decoded string.
     * @return null
     */
    public function outputDecoded($initial, $decoded)
    {
        if (! ESAPI::getLogger(LOG)->isDebugEnabled() || ! $this->allowRecurse) return;
        if ($this->buf === null)
        {
            $this->resetInitial();
            return; // the codec being tested has not added any normalised inputs.
        }
        if ($this->initial != $initial)
        {
            return; // TODO does this need removing now?
        }

        $this->allowRecurse = false;
        $output = $this->buf . "Decoded: [" . ESAPI::getEncoder()->encodeForHTML($decoded) . "]";

        ESAPI::getLogger(LOG)->debug(new EventType("Codec decode() output"), true, $output);
        $this->allowRecurse = true;

        $this->buf = null;
        $this->resetInitial();
    }


    /**
     * convenience method which returns a shortened backtrace.
     */
    private function _shortTrace()
    {
        $dt = debug_backtrace();
        $trace = "";
        $trace .= $dt[4]['class'] . '-&gt;' .  $dt[4]['function'] . ', ';
        $trace .= $dt[3]['class'] . '-&gt;' .  $dt[3]['function'] . ', ';
        $trace .= $dt[2]['class'] . '-&gt;' .  $dt[2]['function']       ; // CodecDebug's caller
        return $trace;
    }
}