<?php
namespace Bolandish;

class Instagram {


/**
     * @param $hashtag  string  Hashtag
     * @param $count    integer Limit
     * @param $assoc    boolean Will return array or stdObjects
     * @param $comment_count integer 
     * @param $cursor   string  Cursor value from first request response. Usually you will need page_info.end_cursor value. 
     * @param &$page_info mixed Current page information
     */
    public static function getMediaByHashtag($hashtag = null, $count = 16, $assoc = false, $comment_count = false, $cursor = null, &$page_info = null)
    {
        if ( empty($hashtag) || !is_string($hashtag) )
        {
            return false;
        }
        if($comment_count){
            $comments = "comments.last($comment_count) {           count,           nodes {             id,             created_at,             text,             user {               id,               profile_pic_url,               username             }           },           page_info         }";
        }else{
            $comments = "comments {       count     }";
        }
        $hashtag = strtolower($hashtag);

        if ($cursor) {
            $mediaFunction = "media.after($cursor, $count)";
        } else {
            $mediaFunction = "media.first($count)";
        }

        $parameters = urlencode("ig_hashtag($hashtag) { $mediaFunction {   count,   nodes {     caption,     code,   $comments,     date,     dimensions {       height,       width     },     display_src,     id,     is_video,     likes {       count     },     owner {       id,       username,       full_name,       profile_pic_url,     biography     },     thumbnail_src,     video_views,     video_url   },   page_info }  }");
        $url = "https://www.instagram.com/query/?q=$parameters&ref=tags%3A%3Ashow";
        $media = json_decode(file_get_contents($url), ($assoc || $assoc == "array"));
        if ($assoc == "array") {
            $page_info = $media["media"]["page_info"];
            $media = $media["media"]["nodes"];
        } else {
            $page_info = $media->media->page_info;
            $media = $media->media->nodes;
        }
        return $media;
    }

    public static function getMediaByUserID($user = null, $count = 16, $assoc = false, $comment_count = false)
    {
        if ( empty($user) || !(is_string($user) || is_int($user)) )
        {
            return false;
        }
        if($comment_count){
            $comments = "comments.last($comment_count) {           count,           nodes {             id,             created_at,             text,             user {               id,               profile_pic_url,               username             }           },           page_info         }";
        }else{
            $comments = "comments {       count     }";
        }
        $parameters = urlencode("ig_user($user) { media.first($count) {   count,   nodes {     caption,     code,     $comments,     date,     dimensions {       height,       width     },     display_src,     id,     is_video,     likes {       count     },     owner {       id,       username,       full_name,       profile_pic_url,     biography     },     thumbnail_src,     video_views,     video_url   },   page_info }  }");
        $url = "https://www.instagram.com/query/?q=$parameters&ref=tags%3A%3Ashow";
        $media = json_decode(file_get_contents($url),($assoc || $assoc == "array"));
        if($assoc == "array")
            $media = $media["media"]["nodes"];
        else
            $media = $media->media->nodes;
        return $media;
    }

    public static function getMediaAfterByUserID($user = null, $min_id, $count = 16, $assoc = false, $comment_count = false)
    {
        if ( empty($user) || !(is_string($user) || is_int($user)) )
        {
            return false;
        }
        if($comment_count){
            $comments = "comments.last($comment_count) {           count,           nodes {             id,             created_at,             text,             user {               id,               profile_pic_url,               username             }           },           page_info         }";
        }else{
            $comments = "comments {       count     }";
        }

        $parameters = urlencode("ig_user($user) { media.after($min_id,$count) {   count,   nodes {     caption,     code,    $comments,   date,     dimensions {       height,       width     },     display_src,     id,     is_video,     likes {       count     },     owner {       id,       username,       full_name,       profile_pic_url,     biography     },     thumbnail_src,     video_views,     video_url   },   page_info }  }");

        $url = "https://www.instagram.com/query/?q=$parameters&ref=tags%3A%3Ashow";
        $media = json_decode(file_get_contents($url),($assoc || $assoc == "array"));
        if($assoc == "array")
            $media = $media["media"]["nodes"];
        else
            $media = $media->media->nodes;

        return $media;
    }

    public static function getCommentsByMediaShortcode($media_shortcode = null, $count = 16, $assoc = false)
    {

        $comments = "comments.last($count) {           count,           nodes {             id,             created_at,             text,             user {               id,               profile_pic_url,               username             }           },           page_info         }";

        $parameters = urlencode("ig_shortcode({$media_shortcode}) { $comments }");
        $url = "https://www.instagram.com/query/?q=$parameters&ref=media%3A%3Ashow";
        $comments = json_decode(file_get_contents($url),($assoc || $assoc == "array"));
        if($assoc == "array")
            $comments = $comments["comments"]["nodes"];
        else
            $comments = $comments->comments->nodes;
        return $comments;
    }

    public static function getCommentsBeforeByMediaShortcode($media_shortcode = null, $max_id, $count = 16, $assoc = false)
    {

        $comments = "comments.before($max_id, $count) {           count,           nodes {             id,             created_at,             text,             user {               id,               profile_pic_url,               username             }           },           page_info         }";

        $parameters = urlencode("ig_shortcode({$media_shortcode}) { $comments }");
        $url = "https://www.instagram.com/query/?q=$parameters&ref=media%3A%3Ashow";
        $comments = json_decode(file_get_contents($url),($assoc || $assoc == "array"));
        if($assoc == "array")
            $comments = $comments["comments"]["nodes"];
        else
            $comments = $comments->comments->nodes;
        return $comments;
    }    

     public static function getMediaByLocationID($location = null, $count = 16, $assoc = false, $comment_count = false)
    {
        if ( empty($location) || !is_int($location))
        {
            return false;
        }
        if($comment_count){
            $comments = "comments.last($comment_count) {           count,           nodes {             id,             created_at,             text,             user {               id,               profile_pic_url,               username             }           },           page_info         }";
        }else{
            $comments = "comments {       count     }";
        }
        $parameters = urlencode("ig_location($location) { media.first($count) {   count,   nodes {     caption,     code,   $comments,     date,     dimensions {       height,       width     },     display_src,     id,     is_video,     likes {       count     },     owner {       id,       username,       full_name,       profile_pic_url,     biography     },     thumbnail_src,     video_views,     video_url   },   page_info }  }");
        $url = "https://www.instagram.com/query/?q=$parameters&ref=locations%3A%3Ashow";
        $media = json_decode(file_get_contents($url), ($assoc || $assoc == "array"));
        if($assoc == "array")
            $media = $media["media"]["nodes"];
        else
            $media = $media->media->nodes;
        return $media;
    }
}
