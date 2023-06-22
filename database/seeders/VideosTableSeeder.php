<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class VideosTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('videos')->delete();
        $videos = array(
            array('id'=> 1, 'title' => 'Leg days', 'thumbnail'=> 'https://cali.vn/storage/app/media/old/lich-tap-gym-hieu-qua---1581935800.jpg', 'url_video' => '/videos/833174274', 'user_id' => '1', 'category_id' => '1', 'level' => 1, 'duration' => 1, 'status' => 1, 'commentable' => 1, 'show' => 1, 'featured' => 1, 'tag'=> 'HIIT, MMA', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()),
            array('id'=> 2, 'title' => 'Rooftop yoga', 'thumbnail'=> 'https://thethaodonga.com/wp-content/uploads/2022/01/anh-gym-nghe-thuat-8.jpg', 'url_video' => '/videos/833174274', 'user_id' => '3', 'category_id' => '1', 'level' => 1, 'duration' => 1, 'status' => 1, 'commentable' => 1, 'show' => 1, 'featured' => 1, 'tag'=> 'HIIT, MMA', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()),
            array('id'=> 3, 'title' => 'No sugar & excuses', 'thumbnail'=> 'https://login.medlatec.vn/ImagePath/imageslead/20201127/20201127_nguyen-tac-vang-giup-ban-tap-gym-dung-cach-va-hieu-qua.jpg', 'url_video' => '/videos/833174274', 'user_id' => '2', 'category_id' => '3', 'level' => 1, 'duration' => 1, 'status' => 1, 'commentable' => 1, 'show' => 1, 'featured' => 1, 'tag'=> 'HIIT, MMA', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()),
            array('id'=> 4, 'title' => 'Strength', 'thumbnail'=> 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRP67WPhKJdCZuMgr3HjnFRZF_umxdZk0XaGw&usqp=CAU', 'url_video' => '/videos/833174274', 'user_id' => '4', 'category_id' => '2', 'level' => 1, 'duration' => 1, 'status' => 1, 'commentable' => 1, 'show' => 1, 'featured' => 1, 'tag'=> 'HIIT, MMA', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()),
            array('id'=> 5, 'title' => 'Video title', 'thumbnail'=> 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSOAtd6VQten06mbc8yfudKiaHiaazaE8zHkQ&usqp=CAU', 'url_video' => '/videos/833174274', 'user_id' => '5', 'category_id' => '5', 'level' => 1, 'duration' => 1, 'status' => 1, 'commentable' => 1, 'show' => 1, 'featured' => 1, 'tag'=> 'HIIT, MMA', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()),
            array('id'=> 6, 'title' => 'Video title', 'thumbnail'=> 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSCw_ec6etCLGrfR1gSQEVtx7uyDGAw40UTlw&usqp=CAU', 'url_video' => '/videos/833174274', 'user_id' => '5', 'category_id' => '4', 'level' => 1, 'duration' => 1, 'status' => 1, 'commentable' => 1, 'show' => 1, 'featured' => 1, 'tag'=> 'HIIT, MMA', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()),
            array('id'=> 7, 'title' => 'Video title', 'thumbnail'=> 'https://www.thethaodaiviet.vn/upload/quan-niem-sai-lam-ve-cho-tre-tap-gym-1.jpg?v=1.0.0', 'url_video' => '/videos/833174274', 'user_id' => '3', 'category_id' => '4', 'level' => 1, 'duration' => 1, 'status' => 1, 'commentable' => 1, 'show' => 1, 'featured' => 1, 'tag'=> 'HIIT, MMA', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()),
            array('id'=> 8, 'title' => 'Video title', 'thumbnail'=> 'https://kickfit-sports.com/wp-content/uploads/2021/05/gym-va-fitness-co-khac-nhau-khong-1.jpg', 'url_video' => '/videos/833174274', 'user_id' => '2', 'category_id' => '3', 'level' => 1, 'duration' => 1, 'status' => 1, 'commentable' => 1, 'show' => 1, 'featured' => 1, 'tag'=> 'HIIT, MMA', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()),
            array('id'=> 9, 'title' => 'Video title', 'thumbnail'=> 'https://pushfitnessyoga.com/wp-content/uploads/2022/08/Tac-dung-cua-Gym-doi-voi-suc-khoe-tam-ly.jpg', 'url_video' => '/videos/833174274', 'user_id' => '1', 'category_id' => '2', 'level' => 1, 'duration' => 1, 'status' => 1, 'commentable' => 1, 'show' => 1, 'featured' => 1, 'tag'=> 'HIIT, MMA', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()),
            array('id'=> 10, 'title' => 'Video title', 'thumbnail'=> 'https://images2.thanhnien.vn/Uploaded/triquang/2017_05_24/tapluyen1_PMMX.jpg', 'url_video' => '/videos/833174274', 'user_id' => '2', 'category_id' => '1', 'level' => 1, 'duration' => 1, 'status' => 1, 'commentable' => 1, 'show' => 1, 'featured' => 1, 'tag'=> 'HIIT, MMA', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()),
            array('id'=> 11, 'title' => 'Video title', 'thumbnail'=> 'https://haycafe.vn/wp-content/uploads/2022/03/Background-dep-gym.jpg', 'url_video' => '/videos/833174274', 'user_id' => '3', 'category_id' => '5', 'level' => 1, 'duration' => 1, 'status' => 1, 'commentable' => 1, 'show' => 1, 'featured' => 1, 'tag'=> 'HIIT, MMA', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()),
            array('id'=> 12, 'title' => 'Video title', 'thumbnail'=> 'https://assets.sweat.com/shopify_articles/images/010/005/285/original/BackToGymSWEATf1f07a7f6f79e7b8807d2436a6ae8e8b.jpg?1625801362', 'url_video' => '/videos/833174274', 'user_id' => '4', 'category_id' => '2', 'level' => 1, 'duration' => 1, 'status' => 1, 'commentable' => 1, 'show' => 1, 'featured' => 1, 'tag'=> 'HIIT, MMA', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()),
            array('id'=> 13, 'title' => 'Video title', 'thumbnail'=> 'https://swequity.vn/wp-content/uploads/2019/11/tap-gym-bao-lau-hieu-qua.jpg', 'url_video' => '/videos/833174274', 'user_id' => '5', 'category_id' => '3', 'level' => 1, 'duration' => 1, 'status' => 1, 'commentable' => 1, 'show' => 0, 'featured' => 0, 'tag'=> 'HIIT, MMA', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()),
            array('id'=> 14, 'title' => 'Video title', 'thumbnail'=> 'https://cali.vn/storage/app/media/old/cover-Lich-tap-gym-cho-nu-1578044243.jpg', 'url_video' => '/videos/833174274', 'user_id' => '2', 'category_id' => '4', 'level' => 1, 'duration' => 1, 'status' => 1, 'commentable' => 1, 'show' => 0, 'featured' => 0, 'tag'=> 'HIIT, MMA', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()),
            array('id'=> 15, 'title' => 'Video title', 'thumbnail'=> 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTihcobXlVhRQ-eOftWnP73vMBX49pk0axfj8H5nqziQLZpFJO-Zc5URBIRYGonLGIJRkw&usqp=CAU', 'url_video' => '/videos/833174274', 'user_id' => '1', 'category_id' => '2', 'level' => 1, 'duration' => 1, 'status' => 1, 'commentable' => 1, 'show' => 0, 'featured' => 0, 'tag'=> 'HIIT, MMA', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()),
            array('id'=> 16, 'title' => 'Video title', 'thumbnail'=> 'https://znews-photo.zingcdn.me/w660/Uploaded/mdf_uswreo/2022_09_20/z3736243565181_a74b9cd8dc1a865999a2a7e39e2ac868.jpg', 'url_video' => '/videos/833174274', 'user_id' => '3', 'category_id' => '3', 'level' => 1, 'duration' => 1, 'status' => 1, 'commentable' => 1, 'show' => 0, 'featured' => 0, 'tag'=> 'HIIT, MMA', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()),
            array('id'=> 17, 'title' => 'Video title', 'thumbnail'=> 'https://bizweb.dktcdn.net/100/021/944/files/tap-gym-ket-hop-yoga-2.jpg?v=1554889822522', 'url_video' => '/videos/833174274', 'user_id' => '2', 'category_id' => '5', 'level' => 1, 'duration' => 1, 'status' => 1, 'commentable' => 1, 'show' => 0, 'featured' => 0, 'tag'=> 'HIIT, MMA', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()),
            array('id'=> 18, 'title' => 'Video title', 'thumbnail'=> 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRh3HAwOfN1ZvE6BlP5WXgYLbs-oEoATSsMhg&usqp=CAU', 'url_video' => '/videos/833174274', 'user_id' => '4', 'category_id' => '1', 'level' => 1, 'duration' => 1, 'status' => 1, 'commentable' => 1, 'show' => 0, 'featured' => 0, 'tag'=> 'HIIT, MMA', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()),
            array('id'=> 19, 'title' => 'Video title', 'thumbnail'=> 'https://cdn.dangcapphaidep.vn/wp-content/uploads/2019/01/tap-gym-1.jpg', 'url_video' => '/videos/833174274', 'user_id' => '2', 'category_id' => '4', 'level' => 1, 'duration' => 1, 'status' => 1, 'commentable' => 1, 'show' => 0, 'featured' => 0, 'tag'=> 'HIIT, MMA', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()),
            array('id'=> 20, 'title' => 'Video title', 'thumbnail'=> 'https://toshiko.vn/wp-content/uploads/2021/09/tap-gym-bi-dau-co-co-nen-tap-tiep-2.jpg', 'url_video' => '/videos/833174274', 'user_id' => '3', 'category_id' => '3', 'level' => 1, 'duration' => 1, 'status' => 1, 'commentable' => 1, 'show' => 0, 'featured' => 0, 'tag'=> 'HIIT, MMA', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()),
        );
        DB::table('videos')->insert($videos);
    }
}
