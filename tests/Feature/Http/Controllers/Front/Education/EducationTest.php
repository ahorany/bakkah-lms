<?php

namespace Tests\Feature\Http\Controllers\Front\Education;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use App\Http\Controllers\Front\Education\EducationController;
use Tests\TestCase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class EducationTest extends TestCase
{
    // public function setup(){
    //     parent::setup();
    // }

    public function test_index(){

        // Storage::fake('public');

        // $file = UploadedFile::fake()->image('avatar.jpg');
        // $response = $this->json('POST', '/upload/file', [
        //     'avatar' => $file,
        // ]);
        // Storage::disk('public')->assertExists($file->hashName());
        // Storage::disk('local')->assertExists('public/upload/full/2020-09-07-12-35-05_115806753_3440802302618544_1357032286705861805_o.png');

        $response = $this->get('/learning');
        $EducationController = new EducationController;

        $array = $EducationController->__index_param();
        foreach($array as $key => $value){

            if($key=='sliders'){
                $this->assertNotEmpty($value);
                $response->assertSee($value->first()->title);
            }
            else if($key=='partners'){
                $this->assertNotEmpty($value);
                $response->assertSee($value->first()->trans_name);
            }
            else if($key=='USPs'){
                $this->assertNotEmpty($value);
                $this->assertEquals($value->count(), 4);
                $response->assertSee($value->first()->title);
            }
            else if($key=='testimonials'){
                $this->assertNotEmpty($value);
                $this->assertEquals($value->count(), 2);
                $response->assertSee($value->first()->trans_excerpt);
            }
            else if($key=='clients'){
                $this->assertNotEmpty($value);
                $response->assertSee($value->first()->trans_name);
            }
        }
        $response->assertStatus(200);
    }

    public function test_hot_deals()
    {
        $response = $this->get('/hot-deals')->assertStatus(200);
    }

    public function test_trainingSchedule()
    {
        $response = $this->get('/sessions/training-schedule')->assertStatus(200);
    }

    public function test_autofill()
    {
        $response = $this->get('/sessions/autofill/email')->assertStatus(200);
    }

    public function test_payment()
    {
        $response = $this->get('/epay/payment')->assertStatus(200);
    }
}
