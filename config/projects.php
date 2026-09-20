<?php

/*
 | Showcase projects listed on /cases/projects.
 | Titles and descriptions live in lang/{locale}/projects.php under items.{slug}.
 | 'image' is a path under public/ (16:9 screenshots work best). Set it to null to fall back
 | to the gradient + icon panel.
 */
return [
    'housecheck' => [
        'url' => 'https://housecheck.arsus.it/',
        'icon' => 'fa-house-circle-check',
        'gradient' => 'from-sky-600/40 to-blue-900/60',
        'image' => 'images/projects/housecheck.jpg',
        'tags' => ['Web App', 'API Integration', 'React', 'Laravel', 'TailwindCSS', 'Dutch'],
    ],
    'kitchenlog' => [
        'url' => 'https://kitchenlog.eu/',
        'icon' => 'fa-utensils',
        'gradient' => 'from-emerald-600/40 to-teal-900/60',
        'image' => 'images/projects/kitchenlog.jpg',
        'tags' => ['AI', 'Compliance', 'Multilingual', 'OpenRouter', 'API Integration', 'Vue.js', 'Laravel', 'TailwindCSS'],
    ],
    'amyshouse' => [
        'url' => 'https://amyshouse.online/',
        'icon' => 'fa-house',
        'gradient' => 'from-pink-600/40 to-rose-900/60',
        'image' => 'images/projects/amyshouse.jpg',
        'tags' => ['Web App', 'E-commerce', 'Vue.js', 'Laravel', 'TailwindCSS', 'Multilingual'],
    ],
    'oxilaris' => [
        'url' => 'https://oxilaris.com/demo',
        'icon' => 'fa-camera-retro',
        'gradient' => 'from-violet-600/40 to-indigo-900/60',
        'image' => 'images/projects/oxilaris.jpg',
        'tags' => ['AI', 'E-commerce', 'SaaS', 'OpenRouter', 'API Integration', 'Laravel', 'TailwindCSS', 'Indonesian'],
    ],
    'jomblo' => [
        'url' => 'https://jomblo.nl/',
        'icon' => 'fa-heart',
        'gradient' => 'from-fuchsia-600/40 to-purple-900/60',
        'image' => 'images/projects/jomblo-ai.jpg',
        'tags' => ['AI', 'Chat', 'API Integration', 'Laravel', 'TailwindCSS', 'Dutch'],
    ],
    'games' => [
        'url' => 'https://games.arsus.it/',
        'icon' => 'fa-gamepad',
        'gradient' => 'from-orange-600/40 to-red-900/60',
        'image' => 'images/projects/games.png',
        'tags' => ['3D', 'Browser Games', 'Three.js'],
    ],
];
