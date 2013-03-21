<?php

//require_once 'Zend/Controller/Action.php';

class GatherController Extends Zend_Controller_Action{

    
    /**
     * get rss feeds
     * @params 
     * @return void
     * @author cnxzcxy<cnxzcxy@gmail.com>
     **/
    public function indexAction(){
        $url = 'http://www.wordpress.com/feed';
        $feed = new Zend_Feed_Rss($url);
        foreach ($feed as $f){
            $title = $f->title;
            $content = $f->content;
            $url = $f->link;
            $cate = $f->category;
            $tag = 'tag';
            $now = time();
            $rs = $this->_rpc($title, $content, $tag, $site);
        }
    }
    
    /**
     * post articles to wordpress
     * @params 
     * @return void
     * @author cnxzcxy<cnxzcxy@gmail.com>
     **/
    private function _rpc($title, $content, $tag, $site){
        $client = new Zend_XmlRpc_Client('http://your.wordpress.com/xmlrpc.php');
        try {
            $res = $client->call('metaWeblog.newPost',array(
                    0, //blog_id, keep 0 for standard wordpress
                    'admin', //username
                    'admin', //pass
                    array(
                        //'post_type' => 'post', //not necessary, 'post' is default
                        'title'=>rawurldecode($title),
                        'description'=>$content,
                        'mt_keywords'=>$tag,
                    ),
                    true //publish
                ));
        } catch (Exception $e){
            print_r($e);
        }
    }
}
