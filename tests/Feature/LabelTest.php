<?php

namespace Tests\Feature;

use App\Models\Label;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class LabelTest extends TestCase
{

    use RefreshDatabase;

    /**
     * A basic feature test example.
     */
    public function test_label_can_be_created(): void
    {
        $user = $this->createAuthUser();
        $label = Label::factory(['user_id' => $user->id])->make()->toArray();
        $this->postJson(route('label.store'), $label)
            ->assertCreated();
        $this->assertDatabaseHas('labels', $label);
    }

    public function test_label_can_be_deleted()
    {
        $user = $this->createAuthUser();
        $label = $this->createLabel(['user_id' => $user]);
        $this->deleteJson(route('label.destroy', $label->id))
            ->assertNoContent();
        $this->assertDatabaseMissing('labels', ['id' => $label->id]);
    }

    public function test_user_can_update_a_label(){
        $user = $this->createAuthUser();
        $label = $this->createLabel(['user_id' => $user->id]);
        $newTitle = 'Updated Label Title ' . rand(1,99);
        $this->patchJson(route('label.update', $label->id), ['title' => $newTitle])
            ->assertOk();
        $this->assertDatabaseHas('labels', ['id' => $label->id, 'title' => $newTitle]);
    }

    public function test_fetch_all_label_of_a_user()
    {

        $user1 = $this->createAuthUser();
        $this->createLabel(['user_id' => $user1->id]);

        $user2 = $this->createAuthUser();
        $this->createLabel(['user_id' => $user2->id]);
        $this->createLabel(['user_id' => $user2->id]);

        $response = $this->getJson(route('label.index'));

        $response->assertOk();
        $this->assertEquals(2, count($response->json()));

    }

}
