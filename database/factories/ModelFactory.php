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

$factory->define(Cupa\Models\User::class, function (Faker\Generator $faker) {
    static $password;

    return [
        'parent' => null,
        'email' => $faker->unique()->safeEmail,
        'salt' => null,
        'password' => $password ?: $password = bcrypt('testing'),
        'first_name' => $faker->firstName,
        'last_name' => $faker->lastName,
        'gender' => $faker->randomElement($array = ['Male', 'Female']),
        'birthday' => $faker->dateTimeBetween($startDate = '-60 years', $endDate = '-13 years'),
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

$factory->define(Cupa\Models\UserProfile::class, function (Faker\Generator $faker) {
    return [
        'user_id' => function() {
            return factory(Cupa\Models\User::class)->create()->id;
        },
        'phone' => $faker->regexify('[0-9]{3}-[0-9]{3}-[0-9]{4}'),
        'nickname' => $faker->name,
        'height' => $faker->numberBetween($min = 40, $max = 90),
        'level' => $faker->randomElement($array = Config::get('cupa.levels')),
        'experience' => date('Y') - $faker->numberBetween($min = 0, $max = 20),
    ];
});

$factory->define(Cupa\Models\UserBalance::class, function (Faker\Generator $faker) {
    return [
    ];
});

$factory->define(Cupa\Models\UserRequirement::class, function (Faker\Generator $faker) {
    $requirements = Config::get('cupa.coachingRequirements');
    $reqs = [];

    foreach ($requirements as $key => $label) {
        $reqs[$key] = $faker->boolean;
    }

    return [
        'year' => date('Y') - $faker->numberBetween($min = 0, $max = 5),
        'user_id' => function() {
            return factory(Cupa\Models\User::class)->create()->id;
        },
        'requirements' => json_encode($reqs),
    ];
});

$factory->define(Cupa\Models\Role::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->randomElement(['admin', 'manager', 'editor', 'reporter', 'background', 'volunteer']),
    ];
});

$factory->define(Cupa\Models\UserRole::class, function (Faker\Generator $faker) {
    return [
        'user_id' => function() {
            return factory(Cupa\Models\User::class)->create()->id;
        },
        'role_id' => function() {
            return factory(Cupa\Models\Role::class)->create()->id;
        },
    ];
});

$factory->define(Cupa\Models\UserWaiver::class, function (Faker\Generator $faker) {
    return [
        'year' => date('Y') - $faker->numberBetween($min = 0, $max = 5),
    ];
});

$factory->define(Cupa\Models\UserContact::class, function (Faker\Generator $faker) {
    return [
        'user_id' => function() {
            return factory(Cupa\Models\User::class)->create()->id;
        },
        'name' => $faker->name,
        'phone' => $faker->regexify('[0-9]{3}-[0-9]{3}-[0-9]{4}'),
    ];
});

$factory->define(Cupa\Models\VolunteerEventCategory::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->unique()->randomElement(['Tournaments', 'Clinics/Camps', 'Leagues', 'Youth Ultimate of Cincinnati', 'US Open']),
        'questions' => '[{"type":"checkboxes","name":"area","title":"In what area would you like to help?","answers":{"boy_scouts":"Boy Scouts","girl_scouts":"Girl Scouts","schools":"Elementary\/Middle Schools","other":"Other"},"required":true},{"type":"textarea","name":"other","title":"Please explain other","required":false},{"type":"checkboxes","name":"available","title":"What time of day are you available?","answers":{"morning":"Morning (Before 12:00pm)","afternoon":"Afternoon (Post 12:00pm)","evening":"Evening (Post 5:00pm)"},"required":true},{"type":"checkboxes","name":"area_town","title":"What area of town would you be willing to help out in?","answers":{"north":"North","east":"East","south":"South","west":"West"},"required":true}]',
    ];
});

$factory->define(Cupa\Models\Volunteer::class, function (Faker\Generator $faker) {
    return [
        'user_id' => function() {
            return factory(Cupa\Models\User::class)->create()->id;
        },
        'involvement' => $faker->randomElement(Config::get('cupa.volunteer.involvement')),
        'primary_interest' => $faker->randomElement(Config::get('cupa.volunteer.primary_interest')),
        'other' => null,
        'experience' => $faker->sentence,
    ];
});

$factory->define(Cupa\Models\VolunteerEvent::class, function (Faker\Generator $faker) {
    $title = $faker->catchPhrase();

    return [
        'volunteer_event_category_id' => function() {
            return factory(Cupa\Models\VolunteerEventCategory::class)->create()->id;
        },
        'title' => $title,
        'slug' => str_slug($title),
        'email_override' => null,
        'start' => $faker->dateTime(),
        'end' => $faker->dateTime(),
        'num_volunteers' => $faker->numberBetween($min = 4, $max = 20),
        'information' => $faker->paragraph(),
        'location_id' => function() {
            return factory(Cupa\Models\Location::class)->create()->id;
        },
    ];
});

$factory->define(Cupa\Models\VolunteerEventSignup::class, function (Faker\Generator $faker) {
    return [
        'volunteer_event_id' => function() {
            return factory(Cupa\Models\VolunteerEvent::class)->create()->id;
        },
        'volunteer_id' => function() {
            return factory(Cupa\Models\Volunteer::class)->create()->id;
        },
        'answers' => '{}',
        'notes' => null,
    ];
});

$factory->define(Cupa\Models\VolunteerEventContact::class, function (Faker\Generator $faker) {
    return [
        'volunteer_event_id' => function() {
            return factory(Cupa\Models\VolunteerEvent::class)->create()->id;
        },
        'user_id' => function() {
            return factory(Cupa\Models\User::class)->create()->id;
        },
    ];
});

$factory->define(Cupa\Models\Clinic::class, function (Faker\Generator $faker) {
    return [
        'type' => 'youth',
        'name' => str_replace(' ', '_', $faker->words($nb = 3, $asText = true)),
        'display' => $faker->sentence,
        'content' => $faker->paragraphs($nb = 3, $asText = true),
    ];
});

$factory->define(Cupa\Models\Form::class, function (Faker\Generator $faker) {
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

$factory->define(Cupa\Models\Location::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->company,
        'street' => $faker->streetAddress,
        'city' => $faker->city,
        'state' => $faker->stateAbbr,
        'zip' => substr($faker->postcode, 0, 5),
        'comments' => null,
    ];
});

$factory->define(Cupa\Models\Tournament::class, function (Faker\Generator $faker) {
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
        'location_id' => function() {
            return factory(Cupa\Models\Location::class)->create()->id;
        },
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

$factory->define(Cupa\Models\Page::class, function (Faker\Generator $faker) {
    $slug = str_slug($faker->words($nb = 2, $asText = true));

    return [
        'route' => str_replace('-', '_', $slug),
        'display' => ucwords(str_replace('-', ' ', $slug)),
        'content' => $faker->paragraphs($nb = 4, $asText = true),
        'is_visible' => 1,
        'weight' => $faker->randomDigitNotNull,
        'created_by' => function() {
            return factory(Cupa\Models\User::class)->create()->id;
        },
        'updated_by' => function() {
            return factory(Cupa\Models\User::class)->create()->id;
        },
    ];
});

$factory->define(Cupa\Models\File::class, function (Faker\Generator $faker) {
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

$factory->define(Cupa\Models\UserMedicalRelease::class, function (Faker\Generator $faker) {
    return [
        'user_id' => function() {
            return factory(Cupa\Models\User::class)->create()->id;
        },
        'year' => $faker->numberBetween($min=date('Y'), $max=date('Y') - 2),
        'data' => '{"ice2_name":"Family Member 1","ice2_email":"fm1@example.com","ice2_phone":"513-555-5555","ice3_name":"Family Member 2","ice3_phone":"513-555-5555","physician_name":"My Physician","physician_phone":"513-555-5555","medical_history":"None"}',
        'updated_by' => function() {
            return factory(Cupa\Models\User::class)->create()->id;
        },
    ];
});

$factory->define(Cupa\Models\League::class, function (Faker\Generator $faker) {
    return [
        'type' => 'league',
        'year' => $faker->year,
        'season' => $faker->randomElement(['winter', 'spring', 'fall', 'summer']),
        'day' => $faker->dayOfWeek,
        'name' => null,
        'slug' => $faker->unique()->slug,
        'override_email' => null,
        'user_teams' => 0,
        'has_pods' => 0,
        'is_youth' => 0,
        'has_registration' => 0,
        'has_waitlist' => 0,
        'default_waitlist' => 0,
        'description' => $faker->paragraph,
        'date_visible' => $faker->datetime,
        'is_archived' => false,
    ];
});

$factory->define(Cupa\Models\LeagueTeam::class, function (Faker\Generator $faker) {
    return [
        'league_id' => function() {
            return factory(Cupa\Models\League::class)->create()->id;
        },
        'name' => $faker->company,
        'logo' => $faker->imageUrl(),
        'color' => 'Black',
        'color_code'=> '#000000'
    ];
});

$factory->define(Cupa\Models\LeagueMember::class, function (Faker\Generator $faker) {
    return [
        'league_id' => function() {
            return factory(Cupa\Models\League::class)->create()->id;
        },
        'user_id' => function() {
            return factory(Cupa\Models\User::class)->create()->id;
        },
        'requirements' => null,
        'position' => $faker->randomElement(['directory', 'captain', 'coach', 'assistant_coach', 'player']),
        'league_team_id' => function() {
            return factory(Cupa\Models\LeagueTeam::class)->create()->id;
        },
        'paid' => $faker->boolean,
        'answers' => '{}',
        'updated_by' => function() {
            return factory(Cupa\Models\User::class)->create()->id;
        },
    ];
});

$factory->define(Cupa\Models\LeagueRegistration::class, function (Faker\Generator $faker) {
    return [
        'league_id' => function() {
            return factory(Cupa\Models\League::class)->create()->id;
        },
        'begin' => $faker->dateTimeBetween($startDate = '-5 months', $endDate = '-2 days'),
        'end' => $faker->dateTimeBetween($startDate = '+2 days', $endDate = '+5 months'),
        'cost' => $faker->numberBetween($min = 10, $max = 60),
        'questions' => '[]',
    ];
});