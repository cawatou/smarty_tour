<?php

class Utils_YouTube
{
    private $video_id = null;

    public function __construct($data)
    {
        if (mb_strpos($data, 'youtube.com/watch?v') !== false) {
            if (preg_match('~youtube.com\/watch\?v=([^\s^&^\/]{11})~', $data, $matches)) {
                $this->video_id = $matches[1];
            }		            
		} elseif (preg_match('~^([^\s^&^\/]{11})$~', $data, $matches)) {
            $this->video_id = $matches[1];
        }

        if (is_null($this->video_id)) {
            throw new dxException('Unknown youtube code');
        }
    }
	
	public function getVideoId()
	{
		return $this->video_id;
	}

    public function getDefaultThumbnail()
    {
        return "http://i1.ytimg.com/vi/{$this->video_id}/default.jpg";
    }

    public function getHQThumbnail()
    {
        return "http://i1.ytimg.com/vi/{$this->video_id}/hqdefault.jpg";
    }

    public function getWatchURL()
    {
        return "http://www.youtube.com/watch?v={$this->video_id}";
    }

    public function getIframeCode()
    {
        return "<iframe width='100%' height='100%' src='http://www.youtube.com/embed/{$this->video_id}?rel=0&wmode=transparent' frameborder='0' allowfullscreen></iframe>";
    }
}