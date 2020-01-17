<?php

namespace Eklundkristoffer\DiscordWebhook;

/**
 * Class DiscordContentObject
 *
 * @package Eklundkristoffer\DiscordWebhook
 *
 *
 * @link https://discordapp.com/developers/docs/resources/webhook#execute-webhook-jsonform-params
 * @link https://discordapp.com/developers/docs/resources/channel#embed-object
 * @link https://discordapp.com/developers/docs/resources/channel#embed-limits
 */
class DiscordContentObject {

    /**
     * Officially supported Discord colours
     * @link https://github.com/izy521/discord.io/blob/master/docs/colors.md
     */
    const COLOR_DEFAULT = 0;
    const COLOR_AQUA = 1752220;
    const COLOR_GREEN = 3066993;
    const COLOR_BLUE = 3447003;
    const COLOR_PURPLE = 10181046;
    const COLOR_GOLD = 15844367;
    const COLOR_ORANGE = 15105570;
    const COLOR_RED = 15158332;
    const COLOR_GREY = 9807270;
    const COLOR_DARKER_GREY = 8359053;
    const COLOR_NAVY = 3426654;
    const COLOR_DARK_AQUA = 1146986;
    const COLOR_DARK_GREEN = 2067276;
    const COLOR_DARK_BLUE = 2123412;
    const COLOR_DARK_PURPLE = 7419530;
    const COLOR_DARK_GOLD = 12745742;
    const COLOR_DARK_ORANGE = 11027200;
    const COLOR_DARK_RED = 10038562;
    const COLOR_DARK_GREY = 9936031;
    const COLOR_LIGHT_GREY = 12370112;
    const COLOR_DARK_NAVY = 2899536;

    /**
     * the message contents (up to 2000 characters)
     * @var string
     */
    protected $content;

    /**
     * @param $text - message contents (up to 2000 characters)
     */
    public function addContent($text){
        $this->content = substr($text, 0, 2000);
    }

    /**
     * embedded rich content
     * array of up to 10 embed objects
     * @var array
     */
    protected $embedded;

    /**
     * @param string $field_name
     * @param mixed $value
     *
     * @throws \OverflowException
     */
    protected function addToEmbedded($field_name, $value){
        $embedded_limit = 10;
        if($field_name == 'timestamp'){
            $used_embedded_limit = $embedded_limit;
        } else {
            // the 10th value is reserved for timestamp
            $used_embedded_limit = $embedded_limit-1;
        }
        if(count($this->embedded) < $used_embedded_limit){
            $this->embedded[$field_name] = $value;
        } else {
            throw new \OverflowException(sprintf("Embedded limit reached. Limit: %d.", $embedded_limit));
        }
    }

    /**
     * title of embed
     * @param string $title
     */
    public function addEmbeddedTitle($title){
        $this->addToEmbedded('title', substr($title, 0, 256));
    }

    /**
     * description of embed
     * @param string $description
     */
    public function addEmbeddedDescription($description){
        $this->addToEmbedded('description', substr($description, 0, 2048));
    }

    /**
     * url of embed
     * @param string $url
     */
    public function addEmbeddedUrl($url){
        $this->addToEmbedded('url', $url);
    }

    /**
     * timestamp of embed content
     * ISO8601 timestamp
     * @param string $timestamp
     */
    public function addEmbeddedTimestamp($timestamp){
        $this->addToEmbedded('timestamp', $timestamp);
    }

    /**
     * color code of the embed
     * See color constants
     * @param int $color
     */
    public function addEmbeddedColor($color){
        $this->addToEmbedded('color', $color);
    }

    /**
     * footer information
     * @param string $text - footer text
     * @param string $icon_url - url of footer icon (only supports http(s) and attachments)
     * @param string $proxy_icon_url - a proxied url of footer icon
     */
    public function addEmbeddedFooter($text, $icon_url="", $proxy_icon_url=""){
        $footer = ['text'=>substr($text, 0, 2048)];
        if(!empty($icon_url)){
            $footer = ['icon_url'=>$icon_url];
        }
        if(!empty($proxy_icon_url)){
            $footer = ['proxy_icon_url'=>$proxy_icon_url];
        }

        $this->addToEmbedded('footer', $footer);
    }

    /**
     * image information
     * @param string $url - source url of image (only supports http(s) and attachments)
     * @param int $height - height of image
     * @param int $width - width of image
     * @param string $proxy_url - a proxied url of the image
     */
    public function addEmbeddedImage($url='', $height=0, $width=0, $proxy_url=''){
        $image = [];
        if(!empty($url)){
            $image['url'] = $url;
        }
        if(!empty($proxy_url)){
            $image['proxy_url'] = $proxy_url;
        }
        if($height != 0){
            $image['height'] = $height;
        }
        if($width != 0){
            $image['width'] = $width;
        }

        if(!empty($image)){
            $this->addToEmbedded('image', $image);
        }
    }

    /**
     * thumbnail information
     * @param string $url - source url of thumbnail (only supports http(s) and attachments)
     * @param int $height - height of thumbnail
     * @param int $width - width of thumbnail
     * @param string $proxy_url - a proxied url of the thumbnail
     */
    public function addEmbeddedThumbnail($url = '', $height=0, $width=0, $proxy_url=''){
        $thumbnail = [];
        if(!empty($url)){
            $thumbnail['url'] = $url;
        }
        if(!empty($proxy_url)){
            $thumbnail['proxy_url'] = $proxy_url;
        }
        if($height != 0){
            $thumbnail['height'] = $height;
        }
        if($width != 0){
            $thumbnail['width'] = $width;
        }

        if(!empty($thumbnail)){
            $this->addToEmbedded('thumbnail', $thumbnail);
        }
    }

    /**
     * video information
     * @param string $url - source url of video
     * @param int $height - height of video
     * @param int $width - width of video
     */
    public function addEmbeddedVideo($url = '', $height=0, $width=0){
        $video = [];
        if(!empty($url)){
            $video['url'] = $url;
        }
        if($height != 0){
            $video['height'] = $height;
        }
        if($width != 0){
            $video['width'] = $width;
        }

        if(!empty($video)){
            $this->addToEmbedded('video', $video);
        }
    }

    /**
     * provider information
     * @param string $name - source url of video
     * @param string $url - source url of video
     */
    public function addEmbeddedProvider($name='', $url=''){
        $provider = [];
        if(!empty($url)){
            $provider['name'] = $url;
        }
        if(!empty($url)){
            $provider['url'] = $url;
        }

        if(!empty($provider)){
            $this->addToEmbedded('provider', $provider);
        }
    }

    /**
     * @param string $name - name of author
     * @param string $url - url of author
     * @param string $icon_url - url of author icon (only supports http(s) and attachments)
     * @param string $proxy_icon_url - a proxied url of author icon
     */
    public function addEmbeddedAuthor($name='', $url='', $icon_url='', $proxy_icon_url=''){
        $author = [];
        if(!empty($name)){
            $author['name'] = substr($name, 0, 256);
        }
        if(!empty($url)){
            $author['url'] = $url;
        }
        if(!empty($icon_url)){
            $author['icon_url'] = $icon_url;
        }
        if(!empty($proxy_icon_url)){
            $author['proxy_icon_url'] = $proxy_icon_url;
        }

        if(!empty($author)){
            $this->addToEmbedded('author', $author);
        }
    }

    /**
     * @param string $name - name of the field
     * @param string $value - value of the field
     * @param bool $inline - whether or not this field should display inline
     *
     * @throws \OverflowException
     */
    public function addEmbeddedField($name, $value, $inline=true){
        $fields_limit = 25;
        if(count($this->embedded['fields']) < $fields_limit){
            $embedded_fields[] = [
                'name'=>substr($name, 0, 256),
                'value'=>substr($value, 0, 1024),
                'inline'=>$inline
            ];
            $this->addToEmbedded('fields', $embedded_fields);
        } else {
            throw new \OverflowException(sprintf("Embedded field limit reached. Limit: %d", $fields_limit));
        }
    }

    public function toArray(){
        $data = [];
        if(!empty($this->content)){
            $data['content'] = $this->content;
        }
        if(!empty($this->embedded)){
            $embeded = $this->embedded;
            $embeded['timestamp'] = date('c');
        }
        return $data;
    }

}