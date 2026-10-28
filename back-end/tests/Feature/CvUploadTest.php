<?php

use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

test('an authenticated user can upload, download, replace, and delete a CV', function () {
    Storage::fake('local');
    $user = User::factory()->create();

    $this->actingAs($user)
        ->post('/api/profile/cv', [
            'cv' => UploadedFile::fake()->create('my-cv.pdf', 100, 'application/pdf'),
        ])
        ->assertCreated()
        ->assertJsonPath('cv.name', 'my-cv.pdf');

    $user->refresh();
    $firstPath = $user->cv_path;

    expect($user->has_cv)->toBeTrue();
    Storage::disk('local')->assertExists($firstPath);

    $this->actingAs($user)
        ->get('/api/profile/cv')
        ->assertOk()
        ->assertDownload('my-cv.pdf');

    $this->actingAs($user)
        ->post('/api/profile/cv', [
            'cv' => UploadedFile::fake()->create('updated-cv.docx', 100, 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'),
        ])
        ->assertCreated();

    $user->refresh();
    Storage::disk('local')->assertMissing($firstPath);
    Storage::disk('local')->assertExists($user->cv_path);
    $lastPath = $user->cv_path;

    $this->actingAs($user)
        ->delete('/api/profile/cv')
        ->assertOk();

    $user->refresh();
    expect($user->has_cv)->toBeFalse();
    Storage::disk('local')->assertMissing($lastPath);
});

test('a CV must be a PDF or Word document no larger than 5 MB', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->post('/api/profile/cv', [
            'cv' => UploadedFile::fake()->create('resume.exe', 100, 'application/octet-stream'),
        ])
        ->assertUnprocessable()
        ->assertJsonValidationErrors('cv');
});
