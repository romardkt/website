<?php

use Illuminate\Support\Facades\Config;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

$factory->define(Cupa\User::class, function (Faker\Generator $faker) {
    return [
        'parent' => null,
        'email' => $faker->email,
        'salt' => null,
        'password' => bcrypt('testing'),
        'first_name' => $faker->firstName,
        'last_name' => $faker->lastName,
        'gender' => $faker->randomElement($array = ['Male', 'Female']),
        'birthday' => $faker->dateTimeBetween($startDate = '-60 years', $endDate = '-13 years'),
        'avatar' => null,
        'activation_code' => str_random(25),
        'activated_at' => $faker->date,
        'reset_password_code' => null,
        'last_reset_password_at' => null,
        'last_login_at' => null,
        'reason' => null,
        'remember_token' => str_random(10),
        'is_active' => 1,
    ];
});

$factory->define(Cupa\UserProfile::class, function (Faker\Generator $faker) {
    return [
        'phone' => $faker->regexify('[0-9]{3}-[0-9]{3}-[0-9]{4}'),
        'nickname' => $faker->name,
        'height' => $faker->numberBetween($min = 40, $max = 90),
        'level' => $faker->randomElement($array = Config::get('cupa.levels')),
        'experience' => date('Y') - $faker->numberBetween($min = 0, $max = 20),
    ];
});

$factory->define(Cupa\UserRequirement::class, function (Faker\Generator $faker) {
    $requirements = Config::get('cupa.coachingRequirements');
    $reqs = [];

    foreach ($requirements as $key => $label) {
        $reqs[$key] = $faker->boolean;
    }

    return [
        'year' => date('Y') - $faker->numberBetween($min = 0, $max = 5),
        'requirements' => json_encode($reqs),
    ];
});

$factory->define(Cupa\Role::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->randomElement(['admin', 'manager', 'editor', 'reporter', 'background', 'volunteer']),
    ];
});

$factory->define(Cupa\UserRole::class, function (Faker\Generator $faker) {
    return [];
});

$factory->define(Cupa\UserWaiver::class, function (Faker\Generator $faker) {
    return [
        'year' => date('Y') - $faker->numberBetween($min = 0, $max = 5),
    ];
});

$factory->define(Cupa\UserContact::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->name,
        'phone' => $faker->regexify('[0-9]{3}-[0-9]{3}-[0-9]{4}'),
    ];
});

$factory->define(Cupa\Volunteer::class, function (Faker\Generator $faker) {
    return [
        'involvement' => $faker->randomElement(Config::get('cupa.volunteer.involvement')),
        'primary_interest' => $faker->randomElement(Config::get('cupa.volunteer.primary_interest')),
        'other' => null,
        'experience' => $faker->sentence,
    ];
});

$factory->define(Cupa\Clinic::class, function (Faker\Generator $faker) {
    return [
        'type' => 'youth',
        'name' => str_replace(' ', '_', $faker->words($nb = 3, $asText = true)),
        'display' => $faker->sentence,
        'content' => $faker->paragraphs($nb = 3, $asText = true),
    ];
});

$factory->define(Cupa\Form::class, function (Faker\Generator $faker) {
    $name = str_replace(' ', '_', $faker->words($nb = 3, $asText = true));
    $extension = $faker->randomElement(['pdf', 'docx', 'doc', 'xls', 'xlsx']);
    $year = date('Y') - $faker->numberBetween($min = 0, $max = 5);
    $slug = $year.'-'.str_slug($name);
    $location = '/testdata/forms/'.$slug.'.'.$extension;

    if (!file_exists(public_path().'/testdata/forms')) {
        mkdir(public_path().'/testdata/forms', 0777, true);
    }
    // create the tmp file
    exec('echo "Just a test file" >'.escapeshellarg(public_path().$location));

    return [
        'year' => $year,
        'name' => $name,
        'slug' => $slug,
        'location' => $location,
        'extension' => $extension,
        'size' => $faker->numberBetween($min = 10000, $max = 10000000),
        'md5' => $faker->md5,
    ];
});

$factory->define(Cupa\Location::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->company,
        'street' => $faker->streetAddress,
        'city' => $faker->city,
        'state' => $faker->stateAbbr,
        'zip' => $faker->postcode,
        'comments' => null,
    ];
});

$factory->define(Cupa\Tournament::class, function (Faker\Generator $faker) {
    $year = date('Y') - $faker->numberBetween($min = 0, $max = 3);
    $startDay = $faker->numberBetween($min = 1, $max = 20);
    $startMonth = $faker->numberBetween($min = 1, $max = 12);
    $startDue = ($startDay - 7 < 1) ? 1 : $startDay - 7;

    return [
        'name' => $faker->randomElement(['nati', 'scinny', 'huckoween', 'vogue', 'cincityclassic', 'highfive', 'nationals', 'rivertown']),
        'year' => $year,
        'display_name' => $faker->company,
        'override_email' => ((mt_rand() * 100) > 80) ? $faker->email : null,
        'image' => '/data/tournaments/default.jpg',
        'divisions' => json_encode($faker->randomElements($array = ['open', 'womens', 'mixed', 'youth_open', 'youth_womens'], $count = $faker->numberBetween($min = 1, $max = 3))),
        'start' => date('Y-m-d', strtotime($year.'-'.$startMonth.'-'.$startDay)),
        'end' => date('Y-m-d', strtotime($year.'-'.$startMonth.'-'.$startDay + 2)),
        'description' => $faker->paragraphs($nb = 3, $asText = true),
        'schedule' => $faker->paragraphs($nb = 2, $asText = true),
        'tenative_date' => 0,
        'use_bid' => 1,
        'cost' => $faker->numberBetween($min = 150, $max = 450),
        'bid_due' => date('Y-m-d H:i:s', strtotime(strtotime($year.'-'.$startMonth.'-'.$startDue))),
        'use_paypal' => 1,
        'has_teams' => 1,
        'paypal' => null,
        'mail' => null,
        'is_visible' => 1,
    ];
});

$factory->define(Cupa\Page::class, function (Faker\Generator $faker) {
    $slug = str_slug($faker->words($nb = 2, $asText = true));

    return [
        'route' => str_replace('-', '_', $slug),
        'display' => ucwords(str_replace('-', ' ', $slug)),
        'content' => $faker->paragraphs($nb = 4, $asText = true),
        'is_visible' => 1,
        'weight' => $faker->randomDigitNotNull,
    ];
});

$factory->define(Cupa\File::class, function (Faker\Generator $faker) {
    $file = str_replace('-', '_', str_slug($faker->words($nb = 2, $asText = true)));
    $data = public_path().'/upload/'.$file.'.'.$faker->fileExtension;

    return [
        'name' => $file.'.'.$faker->fileExtension,
        'mime' => $faker->mimeType,
        'location' => str_replace(public_path(), '', $data),
        'md5' => $faker->md5,
        'size' => $faker->numberBetween($min = 10000, $max = 10000000),
    ];
});
