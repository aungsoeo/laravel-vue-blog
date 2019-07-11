<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
 */

Route::get('/', 'PostController@all');

Route::get('/posts/{post}', 'PostController@single');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::get('/admin/{any}', 'AdminController@index')->where('any', '.*');


Route::get('/{post}/comments', 'CommentController@index');
Route::post('/{post}/comments', 'CommentController@store');

Route::get('rss-feed', function () {


   /* create new feed */
   $feed = App::make("feed");


   /* creating rss feed with our most recent 20 posts */
   $posts = \DB::table('posts')->orderBy('created_at', 'desc')->take(20)->get();


   /* set your feed's title, description, link, pubdate and language */
   $feed->title = 'Linn';
   $feed->description = 'Linn IT & Mobile Mart';
   $feed->logo = 'http://127.0.0.1:8000/logo.jpg';
   $feed->link = url('feed');
   $feed->setDateFormat('datetime');
   $feed->pubdate = $posts[0]->created_at;
   $feed->lang = 'en';
   $feed->setShortening(true);
   $feed->setTextLimit(100);


   foreach ($posts as $post)
   {
       $feed->add($post->title, URL::to($post->id), $post->created_at, $post->title, $post->body);
   }


   return $feed->render('atom');


});
