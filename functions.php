<?php

function rocket_theme_setup()
{
  // タイトルタグ（<title>）の出力.
  add_theme_support('title-tag');
  // アイキャッチ画像の有効化.
  add_theme_support('post-thumbnails');
  // HTML5フォームの有効化.
  add_theme_support('html5', array('search-form'));
  // 固定ページ・投稿ページのアイキャッチサイズ.
  add_image_size('page_eyecatch', 610, 610, true);
  // 記事一覧のアイキャッチサイズ.
  add_image_size('archive_thumbnail', 200, 150, true);
  // カスタムメニュー有効化.
  register_nav_menu('main-menu', 'メインメニュー');

  // カスタムメニュー有効化（複数メニュー）.
  // register_nav_menus( array(
  // 'main-menu' => 'メインメニュー',
  // 'footer-menu' => 'フッターメニュー',
  // ) );
}
add_action('after_setup_theme', 'rocket_theme_setup');

function add_stylesheet() {
  wp_enqueue_style(
      'common', // mainという名前を設定
      get_template_directory_uri(). '/css/common.css', // パス
      array(), // style.cssより先に読み込むCSSは無いので配列は空
  );
}
add_action('wp_enqueue_scripts', 'add_stylesheet');
  // wp_enqueue_scriptsフックにadd_stylesheet関数を登録


function change_posts_per_page($query)
{
  /* 管理画面,メインクエリに干渉しないために必須 */
  if (is_admin() || !$query->is_main_query()) {
    return;
  }

  /* カテゴリーページの表示件数を5件にする */
  if ($query->is_post_type_archive('info')) {
    $query->set('posts_per_page', '5');
    return;
  }
}
add_action('pre_get_posts', 'change_posts_per_page');

//投稿アーカイブページの作成
function post_has_archive($args, $post_type)
{
  if ('post' == $post_type) {
    $args['rewrite'] = true;
    $args['has_archive'] = 'blog'; //スラッグ名
  }
  return $args;
}
add_filter('register_post_type_args', 'post_has_archive', 10, 2);


register_sidebar(array(
  'name' => '検索バー',
  'id' => 'wedget-search',
  'before_widget' => '<div>',
  'after_widget' => '</div>',
  'before_title' => '<h4>',
  'after_title' => '</h4>'
));

if (function_exists('register_sidebar')) {
  register_sidebar(array(
    'name' => 'サイドバー',
    'id' => 'sidebar',
    'description' => 'サイドバーウィジェット',
    'before_widget' => '<div>',
    'after_widget' => '</div>',
    'before_title' => '<h3 class="side-title">',
    'after_title' => '</h3>'
  ));
}