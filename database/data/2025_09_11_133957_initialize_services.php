<?php

declare(strict_types=1);

use App\Models\Service;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Collection;

return new class extends Migration
{
    private const array SERVICES = [
        'service.category.home_and_family' => [
            'service.name.babysitting',
            'service.name.nurse',
            'service.name.elderly_care',
            'service.name.housekeeping',
            'service.name.laundry_ironing',
            'service.name.cooking_assistance',
            'service.name.massage_therapist',
            'service.name.personal_trainer',
        ],

        'service.category.household_maintenance' => [
            'service.name.plumbing',
            'service.name.electrical_repairs',
            'service.name.painting_decorating',
            'service.name.carpentry',
            'service.name.appliance_repair',
        ],

        'service.category.outdoor_environment' => [
            'service.name.gardening',
            'service.name.pool_cleaning',
            'service.name.pest_control',
            'service.name.waste_removal',
        ],

        'service.category.transport_mobility' => [
            'service.name.driver',
            'service.name.delivery',
            'service.name.moving_hauling',
            'service.name.vehicle_washing',
        ],

        'service.category.tech_digital' => [
            'service.name.smartphone_repair',
            'service.name.computer_repair',
        ],

        'service.category.education_tutoring' => [
            'service.name.school_tutoring',
            'service.name.music_lessons',
            'service.name.language_teaching',
            'service.name.special_needs_support',
        ],

        'service.category.pet_services' => [
            'service.name.dog_walking',
            'service.name.pet_sitting',
            'service.name.pet_grooming',
            'service.name.vet_assistant',
        ],

        'service.category.events_lifestyle' => [
            'service.name.photographer',
            'service.name.event_setup',
            'service.name.catering_help',
            'service.name.makeup_hairstyling',
        ],

        'service.category.business_support' => [
            'service.name.office_cleaning',
            'service.name.receptionist_temping',
            'service.name.data_entry',
            'service.name.translation',
            'service.name.courier',
            'service.name.vendors',
            'service.name.secretary',
            'service.name.security_guard',
        ],
    ];

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Collection::make(self::SERVICES)
            ->flatMap(fn (array $names, string $category) => Collection::make($names)->map(fn (string $name): array => [
                'name' => $name,
                'category' => $category,
            ]))
            ->values()
            ->each(fn (array $service) => Service::create($service));
    }
};
