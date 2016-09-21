<?php 
return array (
  'URL_MODEL' => 2,
  'URL_HTML_SUFFIX' => '.html',
  'URL_PATHINFO_DEPR' => '/',
  'URL_ROUTER_ON' => true,
  'URL_ROUTE_RULES' => 
  array (
    '/^index$/' => 'index/index',
    '/^index\/index\/sort\/(\w+)$/' => 'index/index?&sort=:1',
    '/^index\/index\/sort\/(\w+)\/mode\/(\w+)$/' => 'index/index?&sort=:1&mode=:2',
    '/^index\/index\/sort\/(\w+)\/p\/(\d+)$/' => 'index/index?sort=:1&p=:2',
    '/^index\/index\/sort\/(\w+)\/mode\/(\w+)\/p\/(\d+)$/' => 'index/index?&sort=:1&mode=:2&p=:3',
    '/^index\/cate\/cid\/(\d+)\/sort\/(\w+)$/' => 'index/cate?cid=:1&sort=:2',
    '/^index\/cate\/cid\/(\d+)\/sort\/(\w+)\/p\/(\d+)$/' => 'index/cate?cid=:1&sort=:2&p=:3',
    '/^index\/cate\/cid\/(\d+)\/sort\/(\w+)\/mode\/(\w+)$/' => 'index/cate?cid=:1&sort=:2&mode=:3',
    '/^index\/cate\/cid\/(\d+)\/sort\/(\w+)\/mode\/(\w+)\/p\/(\d+)$/' => 'index/cate?cid=:1&sort=:2&mode=:3&p=:4',
    '/^index\/index\/p\/(\d+)$/' => 'index/index?p=:1',
    '/^index\/cate\/cid\/(\d+)\/p\/(\d+)$/' => 'index/cate?cid=:1&p=:2',
    '/^index\/cate\/cid\/(\d+)$/' => 'index/cate?cid=:1',
    '/^item\/(\d+)$/' => 'item/index?id=:1',
    '/^item\/$/' => 'item/index',
    '/^jump\/(\d+)$/' => 'jump/index?id=:1',
    '/^jump\/$/' => 'jump/index',
    '/^view\/(\d+)$/' => 'jump/view?id=:1',
    '/^view\/$/' => 'jump/view',
  ),
);