<?php
/**
 * OWASP Enterprise Security API (ESAPI)
 * 
 * This file is part of the Open Web Application Security Project (OWASP)
 * Enterprise Security API (ESAPI) project. For details, please see
 * <a href="http://www.owasp.org/index.php/ESAPI">http://www.owasp.org/index.php/ESAPI</a>.
 *
 * Copyright (c) 2007 - 2009 The OWASP Foundation
 * 
 * The ESAPI is published by OWASP under the BSD license. You should read and accept the
 * LICENSE before you use, modify, and/or redistribute this software.
 * 
 * @author martin.reiche
 * @created 2009
 * @since 1.4
 * @package org.owasp.esapi.codecs
 */

require_once dirname ( __FILE__ ) . '/Codec.php';
require_once dirname ( __FILE__ ) . '/../ESAPI.php';


/**
 * Implementation of the Codec interface for base 64 encoding.
 * 
 * @author
 * @since 1.4
 * @see org.owasp.esapi.Encoder
 */
class Base64Codec extends Codec
{

	/** FIELD DECLARATIONS **/
	
	 /** No options specified. Value is zero. */
    public static $NO_OPTIONS = 0;
    
    /** Specify encoding. */
    public static $ENCODE = 1;
    
    
    /** Specify decoding. */
    public static $DECODE = 0;
    
    
    /** Specify that data should be gzip-compressed. */
    public static $GZIP = 2;
    
    
    /** Don't break lines when encoding (violates strict Base64 specification) */
    public static $DONT_BREAK_LINES = 8;
	
	/** 
	 * Encode using Base64-like encoding that is URL- and Filename-safe as described
	 * in Section 4 of RFC3548: 
	 * <a href="http://www.faqs.org/rfcs/rfc3548.html">http://www.faqs.org/rfcs/rfc3548.html</a>.
	 * It is important to note that data encoded this way is <em>not</em> officially valid Base64, 
	 * or at the very least should not be called Base64 without also specifying that is
	 * was encoded using the URL- and Filename-safe dialect.
	 */
	 public static $URL_SAFE = 16;
	 
	 
	 /**
	  * Encode using the special "ordered" dialect of Base64 described here:
	  * <a href="http://www.faqs.org/qa/rfcc-1940.html">http://www.faqs.org/qa/rfcc-1940.html</a>.
	  */
	 public static $ORDERED = 32;
    
    
/* ********  P R I V A T E   F I E L D S  ******** */  
    
    
    /** Maximum line length (76) of Base64 output. */
    private static $MAX_LINE_LENGTH = 76;
    
    
    /** The equals sign (=) as a byte. */
    private static $EQUALS_SIGN = '=';
    
    
    /** The new line character (\n) as a byte. */
    private static $NEW_LINE = '\n';
    
    
    /** Preferred encoding. */
    private static $PREFERRED_ENCODING = "UTF-8";
    
    /** End of line character. */
    private static $EOL;
	
	
    // I think I end up not using the BAD_ENCODING indicator.
    //private final static byte BAD_ENCODING    = -9; // Indicates error in encoding
    private static $WHITE_SPACE_ENC = -5; // Indicates white space in encoding
    private static $EQUALS_SIGN_ENC = -1; // Indicates equals sign in encoding
	
    private static $logger;

	
	/* ********  S T A N D A R D   B A S E 6 4   A L P H A B E T  ******** */	
    
    /** The 64 valid Base64 values. */
	/* Host platform me be something funny like EBCDIC, so we hard code these values. */
    private static $_STANDARD_ALPHABET = array(
        'A', 'B', 'C', 'D', 'E', 'F', 'G',
        'H', 'I', 'J', 'K', 'L', 'M', 'N',
        'O', 'P', 'Q', 'R', 'S', 'T', 'U', 
        'V', 'W', 'X', 'Y', 'Z',
        'a', 'b', 'c', 'd', 'e', 'f', 'g',
        'h', 'i', 'j', 'k', 'l', 'm', 'n',
        'o', 'p', 'q', 'r', 's', 't', 'u', 
        'v', 'w', 'x', 'y', 'z',
        '0', '1', '2', '3', '4', '5', 
        '6', '7', '8', '9', '+', '/'
    );


	 /** 
     * Translates a Base64 value to either its 6-bit reconstruction value
     * or a negative number indicating some other meaning.
     **/
    private static $_STANDARD_DECODABET = array(
        -9,-9,-9,-9,-9,-9,-9,-9,-9,                 // Decimal  0 -  8
        -5,-5,                                      // Whitespace: Tab and Linefeed
        -9,-9,                                      // Decimal 11 - 12
        -5,                                         // Whitespace: Carriage Return
        -9,-9,-9,-9,-9,-9,-9,-9,-9,-9,-9,-9,-9,     // Decimal 14 - 26
        -9,-9,-9,-9,-9,                             // Decimal 27 - 31
        -5,                                         // Whitespace: Space
        -9,-9,-9,-9,-9,-9,-9,-9,-9,-9,              // Decimal 33 - 42
        62,                                         // Plus sign at decimal 43
        -9,-9,-9,                                   // Decimal 44 - 46
        63,                                         // Slash at decimal 47
        52,53,54,55,56,57,58,59,60,61,              // Numbers zero through nine
        -9,-9,-9,                                   // Decimal 58 - 60
        -1,                                         // Equals sign at decimal 61
        -9,-9,-9,                                      // Decimal 62 - 64
        0,1,2,3,4,5,6,7,8,9,10,11,12,13,            // Letters 'A' through 'N'
        14,15,16,17,18,19,20,21,22,23,24,25,        // Letters 'O' through 'Z'
        -9,-9,-9,-9,-9,-9,                          // Decimal 91 - 96
        26,27,28,29,30,31,32,33,34,35,36,37,38,     // Letters 'a' through 'm'
        39,40,41,42,43,44,45,46,47,48,49,50,51,     // Letters 'n' through 'z'
        -9,-9,-9,-9                                 // Decimal 123 - 126
        /*,-9,-9,-9,-9,-9,-9,-9,-9,-9,-9,-9,-9,-9,     // Decimal 127 - 139
        -9,-9,-9,-9,-9,-9,-9,-9,-9,-9,-9,-9,-9,     // Decimal 140 - 152
        -9,-9,-9,-9,-9,-9,-9,-9,-9,-9,-9,-9,-9,     // Decimal 153 - 165
        -9,-9,-9,-9,-9,-9,-9,-9,-9,-9,-9,-9,-9,     // Decimal 166 - 178
        -9,-9,-9,-9,-9,-9,-9,-9,-9,-9,-9,-9,-9,     // Decimal 179 - 191
        -9,-9,-9,-9,-9,-9,-9,-9,-9,-9,-9,-9,-9,     // Decimal 192 - 204
        -9,-9,-9,-9,-9,-9,-9,-9,-9,-9,-9,-9,-9,     // Decimal 205 - 217
        -9,-9,-9,-9,-9,-9,-9,-9,-9,-9,-9,-9,-9,     // Decimal 218 - 230
        -9,-9,-9,-9,-9,-9,-9,-9,-9,-9,-9,-9,-9,     // Decimal 231 - 243
        -9,-9,-9,-9,-9,-9,-9,-9,-9,-9,-9,-9         // Decimal 244 - 255 */
    );


	/* ********  U R L   S A F E   B A S E 6 4   A L P H A B E T  ******** */
	
	/**
	 * Used in the URL- and Filename-safe dialect described in Section 4 of RFC3548: 
	 * <a href="http://www.faqs.org/rfcs/rfc3548.html">http://www.faqs.org/rfcs/rfc3548.html</a>.
	 * Notice that the last two bytes become "hyphen" and "underscore" instead of "plus" and "slash."
	 */
    private static $_URL_SAFE_ALPHABET = array(
      'A', 'B', 'C', 'D', 'E', 'F', 'G',
      'H', 'I', 'J', 'K', 'L', 'M', 'N',
      'O', 'P', 'Q', 'R', 'S', 'T', 'U', 
      'V', 'W', 'X', 'Y', 'Z',
      'a', 'b', 'c', 'd', 'e', 'f', 'g',
      'h', 'i', 'j', 'k', 'l', 'm', 'n',
      'o', 'p', 'q', 'r', 's', 't', 'u', 
      'v', 'w', 'x', 'y', 'z',
      '0', '1', '2', '3', '4', '5', 
      '6', '7', '8', '9', '-', '_'
    );
	
	/**
	 * Used in decoding URL- and Filename-safe dialects of Base64.
	 */
    private static $_URL_SAFE_DECODABET = array(
      -9,-9,-9,-9,-9,-9,-9,-9,-9,                 // Decimal  0 -  8
      -5,-5,                                      // Whitespace: Tab and Linefeed
      -9,-9,                                      // Decimal 11 - 12
      -5,                                         // Whitespace: Carriage Return
      -9,-9,-9,-9,-9,-9,-9,-9,-9,-9,-9,-9,-9,     // Decimal 14 - 26
      -9,-9,-9,-9,-9,                             // Decimal 27 - 31
      -5,                                         // Whitespace: Space
      -9,-9,-9,-9,-9,-9,-9,-9,-9,-9,              // Decimal 33 - 42
      -9,                                         // Plus sign at decimal 43
      -9,                                         // Decimal 44
      62,                                         // Minus sign at decimal 45
      -9,                                         // Decimal 46
      -9,                                         // Slash at decimal 47
      52,53,54,55,56,57,58,59,60,61,              // Numbers zero through nine
      -9,-9,-9,                                   // Decimal 58 - 60
      -1,                                         // Equals sign at decimal 61
      -9,-9,-9,                                   // Decimal 62 - 64
      0,1,2,3,4,5,6,7,8,9,10,11,12,13,            // Letters 'A' through 'N'
      14,15,16,17,18,19,20,21,22,23,24,25,        // Letters 'O' through 'Z'
      -9,-9,-9,-9,                                // Decimal 91 - 94
      63,                                         // Underscore at decimal 95
      -9,                                         // Decimal 96
      26,27,28,29,30,31,32,33,34,35,36,37,38,     // Letters 'a' through 'm'
      39,40,41,42,43,44,45,46,47,48,49,50,51,     // Letters 'n' through 'z'
      -9,-9,-9,-9                                 // Decimal 123 - 126
      /*,-9,-9,-9,-9,-9,-9,-9,-9,-9,-9,-9,-9,-9,     // Decimal 127 - 139
      -9,-9,-9,-9,-9,-9,-9,-9,-9,-9,-9,-9,-9,     // Decimal 140 - 152
      -9,-9,-9,-9,-9,-9,-9,-9,-9,-9,-9,-9,-9,     // Decimal 153 - 165
      -9,-9,-9,-9,-9,-9,-9,-9,-9,-9,-9,-9,-9,     // Decimal 166 - 178
      -9,-9,-9,-9,-9,-9,-9,-9,-9,-9,-9,-9,-9,     // Decimal 179 - 191
      -9,-9,-9,-9,-9,-9,-9,-9,-9,-9,-9,-9,-9,     // Decimal 192 - 204
      -9,-9,-9,-9,-9,-9,-9,-9,-9,-9,-9,-9,-9,     // Decimal 205 - 217
      -9,-9,-9,-9,-9,-9,-9,-9,-9,-9,-9,-9,-9,     // Decimal 218 - 230
      -9,-9,-9,-9,-9,-9,-9,-9,-9,-9,-9,-9,-9,     // Decimal 231 - 243
      -9,-9,-9,-9,-9,-9,-9,-9,-9,-9,-9,-9         // Decimal 244 - 255 */
    );



/* ********  O R D E R E D   B A S E 6 4   A L P H A B E T  ******** */

	/**
	 * I don't get the point of this technique, but it is described here:
	 * <a href="http://www.faqs.org/qa/rfcc-1940.html">http://www.faqs.org/qa/rfcc-1940.html</a>.
	 */
    private static $_ORDERED_ALPHABET = array(
      '-',
      '0', '1', '2', '3', '4',
      '5', '6', '7', '8', '9',
      'A', 'B', 'C', 'D', 'E', 'F', 'G',
      'H', 'I', 'J', 'K', 'L', 'M', 'N',
      'O', 'P', 'Q', 'R', 'S', 'T', 'U',
      'V', 'W', 'X', 'Y', 'Z',
      '_',
      'a', 'b', 'c', 'd', 'e', 'f', 'g',
      'h', 'i', 'j', 'k', 'l', 'm', 'n',
      'o', 'p', 'q', 'r', 's', 't', 'u',
      'v', 'w', 'x', 'y', 'z'
    );
	
	/**
	 * Used in decoding the "ordered" dialect of Base64.
	 */
    private static $_ORDERED_DECODABET = array(
      -9,-9,-9,-9,-9,-9,-9,-9,-9,                 // Decimal  0 -  8
      -5,-5,                                      // Whitespace: Tab and Linefeed
      -9,-9,                                      // Decimal 11 - 12
      -5,                                         // Whitespace: Carriage Return
      -9,-9,-9,-9,-9,-9,-9,-9,-9,-9,-9,-9,-9,     // Decimal 14 - 26
      -9,-9,-9,-9,-9,                             // Decimal 27 - 31
      -5,                                         // Whitespace: Space
      -9,-9,-9,-9,-9,-9,-9,-9,-9,-9,              // Decimal 33 - 42
      -9,                                         // Plus sign at decimal 43
      -9,                                         // Decimal 44
      0,                                          // Minus sign at decimal 45
      -9,                                         // Decimal 46
      -9,                                         // Slash at decimal 47
      1,2,3,4,5,6,7,8,9,10,                       // Numbers zero through nine
      -9,-9,-9,                                   // Decimal 58 - 60
      -1,                                         // Equals sign at decimal 61
      -9,-9,-9,                                   // Decimal 62 - 64
      11,12,13,14,15,16,17,18,19,20,21,22,23,     // Letters 'A' through 'M'
      24,25,26,27,28,29,30,31,32,33,34,35,36,     // Letters 'N' through 'Z'
      -9,-9,-9,-9,                                // Decimal 91 - 94
      37,                                         // Underscore at decimal 95
      -9,                                         // Decimal 96
      38,39,40,41,42,43,44,45,46,47,48,49,50,     // Letters 'a' through 'm'
      51,52,53,54,55,56,57,58,59,60,61,62,63,     // Letters 'n' through 'z'
      -9,-9,-9,-9                                 // Decimal 123 - 126
      /*,-9,-9,-9,-9,-9,-9,-9,-9,-9,-9,-9,-9,-9,     // Decimal 127 - 139
        -9,-9,-9,-9,-9,-9,-9,-9,-9,-9,-9,-9,-9,     // Decimal 140 - 152
        -9,-9,-9,-9,-9,-9,-9,-9,-9,-9,-9,-9,-9,     // Decimal 153 - 165
        -9,-9,-9,-9,-9,-9,-9,-9,-9,-9,-9,-9,-9,     // Decimal 166 - 178
        -9,-9,-9,-9,-9,-9,-9,-9,-9,-9,-9,-9,-9,     // Decimal 179 - 191
        -9,-9,-9,-9,-9,-9,-9,-9,-9,-9,-9,-9,-9,     // Decimal 192 - 204
        -9,-9,-9,-9,-9,-9,-9,-9,-9,-9,-9,-9,-9,     // Decimal 205 - 217
        -9,-9,-9,-9,-9,-9,-9,-9,-9,-9,-9,-9,-9,     // Decimal 218 - 230
        -9,-9,-9,-9,-9,-9,-9,-9,-9,-9,-9,-9,-9,     // Decimal 231 - 243
        -9,-9,-9,-9,-9,-9,-9,-9,-9,-9,-9,-9         // Decimal 244 - 255 */
    );
    
    
	/**
	 * Returns one of the _SOMETHING_ALPHABET byte arrays depending on
	 * the options specified.
	 * It's possible, though silly, to specify ORDERED and URLSAFE
	 * in which case one of them will be picked, though there is
	 * no guarantee as to which one will be picked.
	 */
	function getAlphabet( $options )
	{
		if( ($options & $URL_SAFE) == $URL_SAFE ) return $_URL_SAFE_ALPHABET;
		else if( ($options & $ORDERED) == $ORDERED ) return $_ORDERED_ALPHABET;
		else return $_STANDARD_ALPHABET;
		
	}	// end getAlphabet
	
	
	/**
	 * Returns one of the _SOMETHING_DECODABET byte arrays depending on
	 * the options specified.
	 * It's possible, though silly, to specify ORDERED and URL_SAFE
	 * in which case one of them will be picked, though there is
	 * no guarantee as to which one will be picked.
	 */
	function getDecodabet( $options )
	{
		if( ($options & $URL_SAFE) == $URL_SAFE ) return $_URL_SAFE_DECODABET;
		else if( ($options & $ORDERED) == $ORDERED ) return $_ORDERED_DECODABET;
		else return $_STANDARD_DECODABET;
		
	}	// end getAlphabet



 	/**
     * <p>Encodes up to three bytes of the array <var>source</var>
     * and writes the resulting four Base64 bytes to <var>destination</var>.
     * The source and destination arrays can be manipulated
     * anywhere along their length by specifying 
     * <var>srcOffset</var> and <var>destOffset</var>.
     * This method does not check to make sure your arrays
     * are large enough to accomodate <var>srcOffset</var> + 3 for
     * the <var>source</var> array or <var>destOffset</var> + 4 for
     * the <var>destination</var> array.
     * The actual number of significant bytes in your array is
     * given by <var>numSigBytes</var>.</p>
	 * <p>This is the lowest level of the encoding methods with
	 * all possible parameters.</p>
     *
     * @param source the array to convert
     * @param srcOffset the index where conversion begins
     * @param numSigBytes the number of significant bytes in your array
     * @param destination the array to hold the conversion
     * @param destOffset the index where output will be put
     * @return the <var>destination</var> array
     * @since 1.3
     */
    function encode3to4( 
     $source, $srcOffset, $numSigBytes,
     $destination, $destOffset, $options )
    {
		$ALPHABET = getAlphabet( $options ); 
	
        //           1         2         3  
        // 01234567890123456789012345678901 Bit position
        // --------000000001111111122222222 Array position from threeBytes
        // --------|    ||    ||    ||    | Six bit groups to index ALPHABET
        //          >>18  >>12  >> 6  >> 0  Right shift necessary
        //                0x3f  0x3f  0x3f  Additional AND
        
        // Create buffer with zero-padding if there are only one or two
        // significant bytes passed in the array.
        // We have to shift left 24 in order to flush out the 1's that appear
        // when Java treats a value as negative that is cast from a byte to an int.
        $inBuff =   ( $numSigBytes > 0 ? urs(($source[ $srcOffset     ] << 24),  8) : 0 )
                     | ( $numSigBytes > 1 ? urs(($source[ $srcOffset + 1 ] << 24), 16) : 0 )
                     | ( $numSigBytes > 2 ? urs(($source[ $srcOffset + 2 ] << 24), 24) : 0 );

        switch( $numSigBytes )
        {
            case 3:
                $destination[ $destOffset     ] = $ALPHABET[ urs($inBuff , 18)        ];
                $destination[ $destOffset + 1 ] = $ALPHABET[ urs($inBuff , 12) & 0x3f ];
                $destination[ $destOffset + 2 ] = $ALPHABET[ urs($inBuff ,  6) & 0x3f ];
                $destination[ $destOffset + 3 ] = $ALPHABET[ urs($inBuff       ) & 0x3f ];
                return $destination;
                
            case 2:
                $destination[ $destOffset     ] = $ALPHABET[ urs($inBuff , 18)        ];
                $destination[ $destOffset + 1 ] = $ALPHABET[ urs($inBuff , 12) & 0x3f ];
                $destination[ $destOffset + 2 ] = $ALPHABET[ urs($inBuff ,  6) & 0x3f ];
                $destination[ $destOffset + 3 ] = $EQUALS_SIGN;
                return $destination;
                
            case 1:
                $destination[ $destOffset     ] = $ALPHABET[ urs($inBuff , 18)        ];
                $destination[ $destOffset + 1 ] = $ALPHABET[ urs($inBuff , 12) & 0x3f ];
                $destination[ $destOffset + 2 ] = $EQUALS_SIGN;
                $destination[ $destOffset + 3 ] = $EQUALS_SIGN;
                return $destination;
                
            default:
                return $destination;
        }   // end switch
    }   // end encode3to4
    

	


    /**
     * Basic overloading handling. Currently not used due to new naming conventions.

    function __call($m, $a) {
            if ($m == "encode3to4") {
                    if (is_array($a[0]) && is_array($a[1]) &&
                        !is_array($a[2]) && !is_array($a[3])) {
                        return $this->encode3to4($a[1], 0, $a[2], $a[0], 0, $a[3]);
                    } else {
                        return $this->encode3to4($a[0], $a[1], $a[2], $a[3], $a[4], $a[5]);
                    }
            }

    }
     *
     */

    /**
     * Alias for unsignedRightShift.
     */
    private function urs($int, $shft) {
        return unsignedRightShift($int, $shft);
    }

    /**
     * Function for performing an unsigned right shift on an integer.
     */
    protected function unsignedRightShift($int, $shft) {
        $int = $int >> $shft;
        $int = $int & (PHP_INT_MAX >> ($shft - 1) );
        return $int;
    }


    /**
     * Function for encoding a serializable object to Base64
     * @param serializableObject the serializable object to be encoded
     * @param options encoding options
     */
     public static function encodeObject($serializableObject, $options) {
         $gzip = ($options & $GZIP);

         /* HERE TO MOVE ON */
     }


    /**
     * Public Constructor 
     */
    function __construct()
    {
    	parent::__construct();
        $EOL = chr(27);
        $logger = ESAPI::getLogger("Base64");
    }

    /**
     * {@inheritDoc}
     */
    public function encode($immune, $input)
    {
    	
    }

    /**
     * {@inheritDoc}
     */
    public function encodeCharacter($immune, $c)
    {
    }

    /**
     * {@inheritDoc}
     */
    public function decode($input)
    {
    }

    /**
     * {@inheritDoc}
     */
    public function decodeCharacter($input)
    {
    }
}


/**
 * Base 64 Input Stream Filter only for use in context of Base64Codec
 */
class Base64InputStreamFilter extends php_user_filter {

    var $encode = 0;

    /**
     * Initializes and checks whether it has to encode or to decode the stream
     * @return boolean  true if valid filter mode (encoding or decoding)
     *                  has been used.
     */
    function onCreate()
    {
        if ($this->filtername == 'base64.encode') {
            $this->encode = 1;
        } elseif ($this->filtername == 'base64.decode') {
            $this->encode = 0;
        } else {
        return false;
        }
    return true;
    }


    function filter($in, $out, &$consumed, $closing)
    {
        while ($bucket = stream_bucket_make_writeable($in)) {
            $bucket->data = strtoupper($bucket->data);
            $consumed += $bucket->datalen;
        stream_bucket_append($out, $bucket);
        }
        return PSFS_PASS_ON;
    }
    
    
    /* HERE TO MOVE ON. */

}