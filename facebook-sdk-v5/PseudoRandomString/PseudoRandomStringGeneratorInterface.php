<?php
/**
 * Copyright 2014 facebook-sdk-v5, Inc.
 *
 * You are hereby granted a non-exclusive, worldwide, royalty-free license to
 * use, copy, modify, and distribute this software in source code or binary
 * form for use in connection with the web services and APIs provided by
 * facebook-sdk-v5.
 *
 * As with any software that integrates with the facebook-sdk-v5 platform, your use
 * of this software is subject to the facebook-sdk-v5 Developer Principles and
 * Policies [http://developers.facebook.com/policy/]. This copyright notice
 * shall be included in all copies or substantial portions of the software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL
 * THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING
 * FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER
 * DEALINGS IN THE SOFTWARE.
 *
 */
namespace Facebook\PseudoRandomString;

/**
 * Interface
 *
 * @package facebook-sdk-v5
 */
interface PseudoRandomStringGeneratorInterface
{
    /**
     * Get a cryptographically secure pseudo-random string of arbitrary length.
     *
     * @see http://sockpuppet.org/blog/2014/02/25/safely-generate-random-numbers/
     *
     * @param int $length The length of the string to return.
     *
     * @return string
     *
     * @throws \Facebook\Exceptions\FacebookSDKException|\InvalidArgumentException
     */
    public function getPseudoRandomString($length);
}
