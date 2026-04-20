<?php

declare(strict_types=1);

namespace App\Models;

final class SiteModel
{
    /**
     * @return array<string, string>
     */
    public function getCompanyInfo(): array
    {
        return [
            'name' => 'Career Lab',
            'address' => '123 Street, New York, USA',
            'phone' => '+012 345 6789',
            'email' => 'info@example.com',
        ];
    }

    /**
     * @return array<int, array<string, string>>
     */
    public function getHeroSlides(): array
    {
        return [
            [
                'image' => 'img/carousel-1.jpg',
                'subtitle' => 'Creative & Innovative',
                'title' => 'Solutions digitales pour booster votre carriere',
                'primaryLabel' => 'Voir les services',
                'primaryRoute' => 'services',
                'secondaryLabel' => 'Nous contacter',
                'secondaryRoute' => 'contact',
            ],
            [
                'image' => 'img/carousel-2.jpg',
                'subtitle' => 'Learning & Opportunities',
                'title' => 'Reliez les talents aux meilleurs metiers',
                'primaryLabel' => 'Decouvrir l equipe',
                'primaryRoute' => 'team',
                'secondaryLabel' => 'A propos',
                'secondaryRoute' => 'about',
            ],
        ];
    }

    /**
     * @return array<int, array<string, string>>
     */
    public function getFacts(): array
    {
        return [
            [
                'icon' => 'fa-users',
                'label' => 'Candidats accompagnes',
                'value' => '12345',
                'variant' => 'primary',
            ],
            [
                'icon' => 'fa-check',
                'label' => 'Projets realises',
                'value' => '2450',
                'variant' => 'light',
            ],
            [
                'icon' => 'fa-award',
                'label' => 'Partenaires premium',
                'value' => '120',
                'variant' => 'primary',
            ],
        ];
    }

    /**
     * @return array<int, array<string, string>>
     */
    public function getServices(): array
    {
        return [
            [
                'icon' => 'fa-shield-alt',
                'title' => 'Accompagnement des candidats',
                'description' => 'Orientation de carriere, coaching CV et preparation aux entretiens.',
            ],
            [
                'icon' => 'fa-chart-pie',
                'title' => 'Analyse du marche',
                'description' => 'Identification des competences les plus demandees et des secteurs porteurs.',
            ],
            [
                'icon' => 'fa-code',
                'title' => 'Parcours e-learning',
                'description' => 'Modules pratiques pour renforcer les competences techniques et soft skills.',
            ],
            [
                'icon' => 'fa-search',
                'title' => 'Matching metier',
                'description' => 'Mise en relation intelligente entre profils et offres pertinentes.',
            ],
            [
                'icon' => 'fa-users-cog',
                'title' => 'Evaluation des talents',
                'description' => 'Evaluation des aptitudes et recommandations personnalisees.',
            ],
            [
                'icon' => 'fa-briefcase',
                'title' => 'Support recrutement',
                'description' => 'Aide aux entreprises pour identifier les meilleurs profils rapidement.',
            ],
        ];
    }

    /**
     * @return array<int, array<string, string>>
     */
    public function getTeamMembers(): array
    {
        return [
            [
                'name' => 'Nour Ben Ali',
                'role' => 'Career Coach',
                'image' => 'img/team-1.jpg',
            ],
            [
                'name' => 'Sami Trabelsi',
                'role' => 'Talent Analyst',
                'image' => 'img/team-2.jpg',
            ],
            [
                'name' => 'Yasmine Gharbi',
                'role' => 'Learning Specialist',
                'image' => 'img/team-3.jpg',
            ],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function getAboutContent(): array
    {
        return [
            'title' => 'Une solution complete pour accelerer les parcours professionnels',
            'description' => 'Career Lab aide les candidats et les entreprises a mieux se rencontrer grace a des outils d evaluation, de formation et de suivi personnalise.',
            'feature1' => 'Accompagnement sur mesure',
            'feature2' => 'Equipe experte du recrutement',
            'feature3' => 'Support 7j/7',
            'feature4' => 'Approche orientee resultats',
        ];
    }
}
