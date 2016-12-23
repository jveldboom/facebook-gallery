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
namespace Facebook\GraphNodes;

/**
 * Class GraphCoverPhoto
 *
 * @package facebook-sdk-v5
 */
class GraphCoverPhoto extends GraphNode
{
    /**
     * Returns the id of cover if it exists
     *
     * @return int|null
     */
    public function getId()
    {
        return $this->getField('id');
    }
    
    /**
     * Returns the source of cover if it exists
     *
     * @return string|null
     */
    public function getSource()
    {
        return $this->getField('source');
    }

    /**
     * Returns the offset_x of cover if it exists
     *
     * @return int|null
     */
    public function getOffsetX()
    {
        return $this->getField('offset_x');
    }

    /**
     * Returns the offset_y of cover if it exists
     *
     * @return int|null
     */
    public function getOffsetY()
    {
        return $this->getField('offset_y');
    }
}
